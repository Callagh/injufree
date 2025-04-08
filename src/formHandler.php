<?php
session_start();
require_once('db.php');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $username = isset($_POST['username']) ? trim($_POST['username']) : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';
        $confirmedPassword = isset($_POST['confirmedPassword']) ? $_POST['confirmedPassword'] : '';
        $dateOfBirth = isset($_POST['dateOfBirth']) && !empty($_POST['dateOfBirth']) ? $_POST['dateOfBirth'] : '2000-01-01';

        if (isset($_POST['action']) && $_POST['action'] === 'signUp') {
            if (empty($username) || empty($password) || empty($confirmedPassword)) {
                $_SESSION['error'] = "Please fill in all required fields.";
                header("Location: /src/signUp.php");
                exit();
            }

            if ($password !== $confirmedPassword) {
                $_SESSION['error'] = "Passwords do not match. Please try again.";
                header("Location: /src/signUp.php");
                exit();
            }

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Check if username already exists
            $checkStmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
            $checkStmt->bind_param("s", $username);
            $checkStmt->execute();
            $checkResult = $checkStmt->get_result();

            if ($checkResult->num_rows > 0) {
                $_SESSION['error'] = "Username already exists. Please choose another one.";
                header("Location: /src/signUp.php");
                exit();
            }

            $stmt = $conn->prepare("INSERT INTO users (username, password, DOB) VALUES (?, ?, ?)");
            if (!$stmt) {
                throw new Exception("Prepare failed: " . $conn->error);
            }

            $stmt->bind_param("sss", $username, $hashedPassword, $dateOfBirth);
            if (!$stmt->execute()) {
                throw new Exception("Execute failed: " . $stmt->error);
            }

            $_SESSION['username'] = $username;
            $_SESSION['DOB'] = $dateOfBirth;
            header("Location: /src/homePage.php");
            exit();
        } else {
            // Check if user is logged in for non-login/signup actions
            if (!isset($_SESSION['username'])) {
                $_SESSION['error'] = "Please log in to access this page.";
                header("Location: /src/index.php");
                exit();
            }
            if (empty($username) || empty($password)) {
                $_SESSION['error'] = "Please enter both username and password.";
                header("Location: /src/index.php");
                exit();
            }

            $stmt = $conn->prepare("SELECT id, username, password, DOB FROM users WHERE username = ?");
            if (!$stmt) {
                throw new Exception("Prepare failed: " . $conn->error);
            }

            $stmt->bind_param("s", $username);
            if (!$stmt->execute()) {
                throw new Exception("Execute failed: " . $stmt->error);
            }

            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                if (password_verify($password, $user['password'])) {
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['DOB'] = $user['DOB'];
                    header("Location: /src/homePage.php");
                    exit();
                } else {
                    $_SESSION['error'] = "Incorrect password.";
                    header("Location: /src/index.php");
                    exit();
                }
            } else {
                $_SESSION['error'] = "No account found with this username.";
                header("Location: /src/index.php");
                exit();
            }
        }
    } catch (Exception $e) {
        error_log("Form Handler Error: " . $e->getMessage());
        $_SESSION['error'] = "An error occurred. Please try again later.";
        header("Location: /src/signUp.php");
        exit();
    }
}
?>
