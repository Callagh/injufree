<?php

require "./db.php";
session_start();

if (!isset($_POST['action'])) {
    setErrorAndRedirect("Invalid request.");
}

switch ($_POST['action']) {
    case 'signIn':
        echo "You signed in";
        break;

    case 'signUp':
        

        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);

        if ($_POST['password'] !== $_POST['confirmedPassword']) {
            setErrorAndRedirect("Passwords do not match");
        }

        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        // Check for extra fields and consent
        $DOB = $genderAtBirth = null;
        if (!empty($_POST['extra']) && !empty($_POST['consent'])) {
            $DOB = mysqli_real_escape_string($conn, $_POST['DOB']);
            $genderAtBirth = mysqli_real_escape_string($conn, $_POST['genderAtBirth']);
        } elseif (!empty($_POST['extra']) && empty($_POST['consent'])) {
            setErrorAndRedirect("You have chosen to submit more information but have not consented to the storage of it");
        }

        
        insertUser($conn, $name, $email, $password, $DOB, $genderAtBirth);

        $_SESSION['name'] = $name;
        header("Location: ./homePage.php");
        exit();

    default:
        setErrorAndRedirect("Invalid action.");
}

mysqli_close($conn);

function setErrorAndRedirect($message) {
    $_SESSION['error'] = $message;
    header("Location: ./index.php");
    exit();
}

function insertUser($conn, $name, $email, $password, $DOB = null, $genderAtBirth = null) {
    if ($DOB && $genderAtBirth) {
        $sql = "INSERT INTO users (name, email, password, DOB, genderAtBirth) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssss", $name, $email, $password, $DOB, $genderAtBirth);
    } else {
        $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sss", $name, $email, $password);
    }

    if (!mysqli_stmt_execute($stmt)) {
        setErrorAndRedirect("Error: " . mysqli_error($conn));
    }
}
?>
