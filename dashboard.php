<?php
session_start();
include('db.connection.php'); // Ensure this includes your database connection settings

// Check if the user is logged in, otherwise redirect to login page
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit();
}

// User information from session
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$email = $_SESSION['email'];

// SQL Query to fetch user data and related information (e.g., total orders, total products)
$sql = "SELECT users.username, users.email, COUNT(orders.id) AS total_orders, COUNT(products.id) AS total_products
        FROM users
        LEFT JOIN orders ON users.id = orders.customer_id
        LEFT JOIN orderitems ON orders.id = orderitems.order_id
        LEFT JOIN products ON orderitems.product_id = products.id
        WHERE users.id = :user_id
        GROUP BY users.id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['user_id' => $user_id]);

// Check if the query returns any result
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
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"> <!-- Google Icons for buttons -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .navbar {
            background-color: #333;
            padding: 15px;
            color: white;
            text-align: center;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            padding: 10px;
        }

        .navbar .menu {
            display: flex;
            gap: 15px;
        }

        .navbar a:hover {
            background-color: #555;
            border-radius: 5px;
        }

        .navbar .menu > a:nth-child(1) { background-color: green; }
        .navbar .menu > a:nth-child(2) { background-color: blue; }
        .navbar .menu > a:nth-child(3) { background-color: yellow; }

        .container {
            margin: 20px;
        }

        .dashboard-card {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .card-title {
            font-size: 24px;
            margin-bottom: 10px;
        }

        .card-info {
            font-size: 18px;
            margin-bottom: 5px;
        }

        /* Modal styling */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0, 0, 0);
            background-color: rgba(0, 0, 0, 0.4);
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .logout-button, .back-button {
            background-color: #e74c3c;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .logout-button:hover, .back-button:hover {
            background-color: #c0392b;
        }

        .animated-button {
            transition: transform 0.3s ease;
        }

        .animated-button:hover {
            transform: scale(1.1);
        }

        /* Back to Landing Page Modal */
        .back-modal .modal-content {
            text-align: center;
        }

        .back-modal a {
            color: blue;
            font-size: 18px;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <div class="navbar">
        <h2>Ordering Management System</h2>
        <div class="menu">
            <a href="#" id="manage-products">Manage Products</a>
            <a href="#" id="manage-customers">Manage Customers</a>
            <a href="#" id="manage-orders">Manage Orders</a>
        </div>
        <button class="logout-button animated-button" id="logout-btn"><span class="material-icons">exit_to_app</span> Logout</button>
    </div>

    <!-- Main container -->
    <div class="container">
        <div class="dashboard-card">
            <div class="card-title">Welcome, <?php echo htmlspecialchars($username); ?>!</div>
            <div class="card-info">Email: <?php echo htmlspecialchars($email); ?></div>
            <div class="card-info">Total Orders: <?php echo $total_orders; ?></div>
            <div class="card-info">Total Products Ordered: <?php echo $total_products; ?></div>
        </div>

        <!-- Back to Landing Page button -->
        <button class="back-button animated-button" id="back-to-landing">Back to Landing Page</button>
    </div>

    <!-- Logout Modal -->
    <div id="logout-modal" class="modal">
        <div class="modal-content">
            <span class="close" id="close-logout">&times;</span>
            <h2>Are you sure you want to log out?</h2>
            <form action="logout.php" method="POST">
                <button type="submit" class="logout-button">Yes, Logout</button>
            </form>
        </div>
    </div>

    <!-- User Info Modal -->
    <div id="user-info-modal" class="modal">
        <div class="modal-content">
            <span class="close" id="close-user-info">&times;</span>
            <h2>User Info</h2>
            <p><strong>Username:</strong> <?php echo htmlspecialchars($username); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
        </div>
    </div>

    <!-- Back to Landing Page Modal -->
    <div id="back-modal" class="modal back-modal">
        <div class="modal-content">
            <h2>Are you sure you want to go back to the landing page?</h2>
            <a href="index.php">Yes, go to Landing Page</a>
            <button class="close" id="close-back">Close</button>
        </div>
    </div>

    <script>
        // Logout Modal
        var logoutModal = document.getElementById("logout-modal");
        var logoutBtn = document.getElementById("logout-btn");
        var closeLogout = document.getElementById("close-logout");

        logoutBtn.onclick = function() {
            logoutModal.style.display = "block";
        }

        closeLogout.onclick = function() {
            logoutModal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == logoutModal) {
                logoutModal.style.display = "none";
            }
        }

        // User Info Modal
        var userInfoModal = document.getElementById("user-info-modal");
        var closeUserInfo = document.getElementById("close-user-info");

        // Back to Landing Page Modal
        var backModal = document.getElementById("back-modal");
        var backBtn = document.getElementById("back-to-landing");
        var closeBack = document.getElementById("close-back");

        backBtn.onclick = function() {
            backModal.style.display = "block";
        }

        closeBack.onclick = function() {
            backModal.style.display = "none";
        }
    </script>

</body>
</html>
