<?php
include('db.connection.php');  // Include the database connection

// Check if order_id is passed in the URL
if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];  // Get the order ID from the URL
} else {
    // If no order_id is passed, redirect or show an error
    echo "Order ID is missing!";
    exit();
}

// Fetch all order items linked to the order
$query = "SELECT oi.id, oi.quantity, oi.price, p.name as product_name 
          FROM OrderItems oi
          JOIN Products p ON oi.product_id = p.id 
          WHERE oi.order_id = :order_id";

$stmt = $pdo->prepare($query);  // Use PDO prepared statements to prevent SQL injection
$stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Items</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-4">
    <h2>Order Items</h2>
    <h3>Order ID: <?= htmlspecialchars($order_id) ?></h3>

    <!-- Order Items Table -->
    <table class="table table-striped mt-4">
        <thead>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Price</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result): ?>
                <?php foreach ($result as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['product_name']) ?></td>
                        <td><?= htmlspecialchars($row['quantity']) ?></td>
                        <td>$<?= number_format($row['price'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3" class="text-center">No items found for this order.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Bootstrap JS and Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>

</body>
</html>
