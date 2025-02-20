<html><h1>formhandler sent</h1></html>
<?php

require "./db.php";

$userFetch = "SELECT * FROM users";
$result = $conn->query($userFetch);

    if (isset($_POST['signIn'])){
        echo "you signed in";


    }

    if (isset($_POST['signUp'])){
        echo "you signed up";
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
            if ($_POST['password'] == $_POST['confirmedPassword']){
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            

            
            $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";

            if (mysqli_query($conn, $sql)){
                echo "You've signed up!";
            }else{
                session_start();
                $_SESSION['error']= "Error: " . mysqli_error($conn);
                header("Location: ./index.html");
                exit();
            }
        }else{
            session_start();
            $_SESSION['error'] = "Passwords do not match";
            header("Location: ./index.html");
            exit();
        }
    }    
                


            
    

mysqli_close($conn);
 ?>