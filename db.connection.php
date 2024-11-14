<?php
// db.connection.php

$servername = "localhost"; // Database server
$username = "root"; // Your MySQL username
$password = ""; // Your MySQL password
$dbname = "ordering_management_system"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
die("Connection failed: " . $conn->connect_error);
}
?>
