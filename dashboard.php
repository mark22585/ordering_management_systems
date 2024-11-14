<?php
// dashboard.php
session_start();

// Check if user is logged in, if not, redirect to login page
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h1>Admin Dashboard</h1>

    <div class="row mt-3">
        <div class="col-md-4">
            <a href="products.php" class="btn btn-success w-100">Manage Products</a>
        </div>
        <div class="col-md-4">
            <a href="customers.php" class="btn btn-primary w-100">Manage Customers</a>
        </div>
        <div class="col-md-4">
            <a href="orders.php" class="btn btn-warning w-100">Manage Orders</a>
        </div>
    </div>
</div>

<!-- Bootstrap JS and Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>

</body>
</html>
