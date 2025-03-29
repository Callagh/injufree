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

        // Set session variables
        $_SESSION['name'] = $name;
        $_SESSION['DOB'] = $DOB;
        $_SESSION['genderAtBirth'] = $genderAtBirth;
        
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

function verifyUser($conn, $email, $password){

    $stmt = $conn->prepare("SELECT password, name, id, DOB, genderAtBirth FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if($stmt->num_rows >0){
        $stmt->bind_result($hashedPassword, $name, $id, $DOB, $genderAtBirth);
        $stmt->fetch();
        mysqli_stmt_close($stmt);  

        if(password_verify($password, $hashedPassword)){
            $_SESSION['name'] = $name;
            $_SESSION['id'] = $id;
            $_SESSION['DOB'] = $DOB;
            $_SESSION['genderAtBirth']= $genderAtBirth;
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
