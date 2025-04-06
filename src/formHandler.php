<?php

require "db.php";
session_start();
$email = mysqli_real_escape_string($conn, $_POST['email']);

if (!isset($_POST['action'])) {
    setErrorAndRedirect("Invalid request.");
}

switch ($_POST['action']) {
    case 'signIn':
        $password = $_POST['password'];
        verifyUser($conn, $email, $password);

        break;

    case 'signUp':
        

        $name = mysqli_real_escape_string($conn, $_POST['name']);

        if(!uniqueEmail($conn, $_POST['email'])){
            setErrorAndRedirect("Email is already is use");
        }
        

        if ($_POST['password'] !== $_POST['confirmedPassword']) {
            $_SESSION['error'] = "Passwords do not match";
            header("Location: ./signUp.php");
            exit();
        }

        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        // Check for extra fields and consent
        $DOB = null;
        if (!empty($_POST['DOB'])) {
            // Calculate age from DOB
            $dob = new DateTime($_POST['DOB']);
            $today = new DateTime();
            $age = $today->diff($dob)->y;
            
            if ($age < 16) {
                $_SESSION['error'] = "You must be 16 or older to create an account";
                header("Location: ./signUp.php");
                exit();
            }
            
            $DOB = mysqli_real_escape_string($conn, $_POST['DOB']);
        }

        if (!empty($_POST['extra']) && empty($_POST['consent'])) {
            $_SESSION['error'] = "You have chosen to submit more information but have not consented to the storage of it";
            header("Location: ./signUp.php");
            exit();
        }
        
        insertUser($conn, $name, $email, $password, $DOB);

        // Set session variables
        $_SESSION['name'] = $name;
        $_SESSION['DOB'] = $DOB;
        
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

function insertUser($conn, $name, $email, $password, $DOB = null) {
    if ($DOB) {
        $sql = "INSERT INTO users (name, email, password, DOB) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssss", $name, $email, $password, $DOB);
    } else {
        $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sss", $name, $email, $password);
    }

    if (!mysqli_stmt_execute($stmt)) {
        setErrorAndRedirect("Error: " . mysqli_error($conn));
    }
}

function verifyUser($conn, $email, $password){
    $stmt = $conn->prepare("SELECT password, name, id, DOB FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if($stmt->num_rows >0){
        $stmt->bind_result($hashedPassword, $name, $id, $DOB);
        $stmt->fetch();
        mysqli_stmt_close($stmt);  

        if(password_verify($password, $hashedPassword)){
            $_SESSION['name'] = $name;
            $_SESSION['id'] = $id;
            $_SESSION['DOB'] = $DOB;
            header("Location: ./homePage.php");
            exit();

        }else{
            setErrorAndRedirect("Incorrect password");
        }

    }else{
        setErrorAndRedirect("User not found");
    }
}

function uniqueEmail($conn, $email){

    $stmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    $isUnique = ($stmt->num_rows == 0);  
    mysqli_stmt_close($stmt);  

    return $isUnique;
}
?>
