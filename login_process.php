<?php
// login_process.php
session_start();
include('db.connection.php'); // Make sure this is the correct database connection file

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query to check if the user exists by username or email
    $query = "SELECT * FROM users WHERE username = :username OR email = :email";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['username' => $username, 'email' => $username]);

    // Fetch user data
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if user exists and verify the password
    if ($user && password_verify($password, $user['password'])) {
        // Set session variables
        $_SESSION['loggedin'] = true;
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];

        // Redirect to dashboard
        header('Location: dashboard.php');
        exit();
    } else {
        // Redirect back to login with error
        header('Location: login.php?error=true');
        exit();
    }
} else {
    // Redirect to login page if accessed directly
    header('Location: login.php');
    exit();
}
?>
