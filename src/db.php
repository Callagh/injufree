<?php
$servername = "sql309.infinityfree.com";
$username = "if0_38602377";
$password = "zkTW6vj5gMTVb";
$dbname = "if0_38602377_injufree";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>