<?php
// login_process.php
session_start();
include('db.connection.php'); // Ensure this file contains the correct DB connection settings

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query to check if the user exists
    $query = "SELECT * FROM users WHERE (username = ? OR email = ?) AND password = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sss', $username, $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a user was found and password matches
    if ($result->num_rows > 0) {
        // Start a session and redirect to the dashboard
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;
        header('Location: dashboard.php');
        exit();
    } else {
        // Redirect back to login with an error message
        header('Location: login.php?error=true');
        exit();
    }
} else {
    // Redirect to login page if accessed directly
    header('Location: login.php');
    exit();
}
