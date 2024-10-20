<?php
// db.php
$host = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName = "gravity";

$conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
