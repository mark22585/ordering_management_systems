<?php
session_start();
include('db.connection.php');

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit();
}

// Retrieve session variables
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$email = $_SESSION['email'];

// SQL Query to fetch user data and related information (e.g., total orders, total products)
$sql = "SELECT users.username, users.email, 
               COALESCE(COUNT(orders.id), 0) AS total_orders, 
               COALESCE(COUNT(products.id), 0) AS total_products
        FROM users
        LEFT JOIN orders ON users.id = orders.customer_id
        LEFT JOIN orderitems ON orders.id = orderitems.order_id
        LEFT JOIN products ON orderitems.product_id = products.id
        WHERE users.id = :user_id
        GROUP BY users.id";

$stmt = $pdo->prepare($sql);
$stmt->execute(['user_id' => $user_id]);

// Fetch the user data
$user_data = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user_data) {
    $total_orders = $user_data['total_orders'];
    $total_products = $user_data['total_products'];
} else {
    echo "Error fetching user data.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #121212;
            color: white;
            font-family: 'Arial', sans-serif;
        }

        .navbar {
            background-color: #333;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar .nav-link, .navbar button {
            color: white;
        }

        .dashboard-title {
            text-align: center;
            font-size: 2.5rem;
            margin: 20px 0;
        }

        .dashboard-card {
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            text-align: center;
            color: white;
            transition: transform 0.3s ease;
        }

        .dashboard-card:hover {
            transform: scale(1.05) rotate(3deg);
        }

        .card-icon {
            font-size: 4rem;
            margin-bottom: 10px;
        }

        .container {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            gap: 20px;
        }

        .modal-content {
            background-color: #333;
            color: white;
            border-radius: 8px;
        }

        .dark-mode {
            background-color: #121212;
            color: white;
        }

        .logout-button {
            background-color: #e60000;
            color: white;
            border: none;
        }

        .logout-button:hover {
            background-color: #cc0000;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Ordering Management System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="products.php">Manage Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="customers.php">Manage Customers</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="orders.php">Manage Orders</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">
                            <span class="material-icons">exit_to_app</span> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Dashboard Content -->
    <div class="container mt-4">
        <h2 class="dashboard-title">Welcome, <?php echo htmlspecialchars($username); ?>!</h2>
        <div class="dashboard-card">
            <span class="material-icons card-icon">shopping_cart</span>
            <p>Total Products Ordered: <?php echo $total_products; ?></p>
        </div>
        <div class="dashboard-card">
            <span class="material-icons card-icon">people</span>
            <p>Total Customers: 45</p> <!-- Replace with actual dynamic data if available -->
        </div>
        <div class="dashboard-card">
            <span class="material-icons card-icon">list_alt</span>
            <p>Total Orders: <?php echo $total_orders; ?></p>
        </div>

        <!-- Back to Landing Page Button -->
        <div class="dashboard-card">
            <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#landingPageModal">
                <span class="material-icons">home</span> Back to Landing Page
            </button>
        </div>
    </div>

    <!-- Back to Landing Page Modal -->
    <div class="modal fade" id="landingPageModal" tabindex="-1" aria-labelledby="landingPageModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="landingPageModalLabel">Back to Landing Page</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to go back to the landing page?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <a href="index.php" class="btn btn-primary">Go to Landing Page</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Logout Modal -->
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logoutModalLabel">Logout</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to logout?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <a href="logout.php" class="btn btn-danger">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
