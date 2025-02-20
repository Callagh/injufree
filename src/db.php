<?php
$serverName= "localhost";
$username= "callum";
$password= "password";
$dbName= "injufree";

$conn = mysqli_connect($serverName, $username, $password, $dbName);

if (!$conn){
    die("Connection failed: " . mysqli_connect_error());
}

echo "connected successfully";
?>