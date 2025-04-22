/**
 * formHandler.php
 * 
 * This file handles user authentication and account management for the Injufree application.
 * It processes both login and signup requests, manages user sessions, and interacts with the database.
 * 
 * Key functionalities:
 * - User registration with password hashing
 * - Username availability checking
 * - Login authentication
 * - Session management
 * - Error handling and user feedback
 * 
 */

<?php
// Initialize session and include database connection
session_start();
require_once('db.php');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Process POST requests
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Sanitize and validate input data
        $username = isset($_POST['username']) ? trim($_POST['username']) : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';
        $confirmedPassword = isset($_POST['confirmedPassword']) ? $_POST['confirmedPassword'] : '';
        $dateOfBirth = isset($_POST['dateOfBirth']) && !empty($_POST['dateOfBirth']) ? $_POST['dateOfBirth'] : '2000-01-01';

        // Handle signup process
        if (isset($_POST['action']) && $_POST['action'] === 'signUp') {
            // Validate required fields are not empty
            if (empty($username) || empty($password) || empty($confirmedPassword)) {
                $_SESSION['error'] = "Please fill in all required fields.";
                header("Location: /src/signUp.php");
                exit();
            }

            // Verify password confirmation matches
            if ($password !== $confirmedPassword) {
                $_SESSION['error'] = "Passwords do not match. Please try again.";
                header("Location: /src/signUp.php");
                exit();
            }

            // Hash password for secure storage
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Check if username already exists in database
            $checkStmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
            $checkStmt->bind_param("s", $username);
            $checkStmt->execute();
            $checkResult = $checkStmt->get_result();

            if ($checkResult->num_rows > 0) {
                $_SESSION['error'] = "Username already exists. Please choose another one.";
                header("Location: /src/signUp.php");
                exit();
            }

            // Insert new user into database
            $stmt = $conn->prepare("INSERT INTO users (username, password, DOB) VALUES (?, ?, ?)");
            if (!$stmt) {
                throw new Exception("Prepare failed: " . $conn->error);
            }

            $stmt->bind_param("sss", $username, $hashedPassword, $dateOfBirth);
            if (!$stmt->execute()) {
                throw new Exception("Execute failed: " . $stmt->error);
            }

            // Set session variables and redirect to home page
            $_SESSION['username'] = $username;
            $_SESSION['DOB'] = $dateOfBirth;
            header("Location: /src/homePage.php");
            exit();
        } else {
            // Verify user is not already logged in
            if (!isset($_SESSION['username'])) {
                $_SESSION['error'] = "Please log in to access this page.";
                header("Location: /src/index.php");
                exit();
            }

            // Validate login credentials are not empty
            if (empty($username) || empty($password)) {
                $_SESSION['error'] = "Please enter both username and password.";
                header("Location: /src/index.php");
                exit();
            }

            // Query database for user credentials
            $stmt = $conn->prepare("SELECT id, username, password, DOB FROM users WHERE username = ?");
            if (!$stmt) {
                throw new Exception("Prepare failed: " . $conn->error);
            }

            $stmt->bind_param("s", $username);
            if (!$stmt->execute()) {
                throw new Exception("Execute failed: " . $stmt->error);
            }

            $result = $stmt->get_result();

            // Verify user exists and password is correct
            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                if (password_verify($password, $user['password'])) {
                    // Set session variables and redirect to home page
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
        // Log error and redirect with error message
        error_log("Form Handler Error: " . $e->getMessage());
        $_SESSION['error'] = "An error occurred. Please try again later.";
        header("Location: /src/signUp.php");
        exit();
    }
}
?>
