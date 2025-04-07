<?php
session_start();
require_once('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $dateOfBirth = isset($_POST['dateOfBirth']) ? $_POST['dateOfBirth'] : null;

    if (isset($_POST['signup'])) {
        if (empty($email) || empty($password) || empty($name)) {
            $_SESSION['error'] = "Please fill in all required fields.";
            header("Location: ./signUp.php");
            exit();
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (email, password, name, DOB) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $email, $hashedPassword, $name, $dateOfBirth);

        if ($stmt->execute()) {
            $_SESSION['email'] = $email;
            $_SESSION['name'] = $name;
            $_SESSION['DOB'] = $dateOfBirth;
            header("Location: ./homePage.php");
            exit();
        } else {
            $_SESSION['error'] = "Error creating account. Please try again.";
            header("Location: ./signUp.php");
            exit();
        }
    } else {
        if (empty($email) || empty($password)) {
            $_SESSION['error'] = "Please enter both email and password.";
            header("Location: ./index.php");
            exit();
        }

        $stmt = $conn->prepare("SELECT id, email, password, name, DOB FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['email'] = $user['email'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['DOB'] = $user['DOB'];
                header("Location: ./homePage.php");
                exit();
            } else {
                $_SESSION['error'] = "Incorrect password.";
                header("Location: ./index.php");
                exit();
            }
        } else {
            $_SESSION['error'] = "No account found with this email.";
            header("Location: ./index.php");
            exit();
        }
    }
}
?>
