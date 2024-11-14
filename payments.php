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

// Fetch all payments linked to the order
$query = "SELECT * FROM Payments WHERE order_id = :order_id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payments</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-4">
    <h2>Payments for Order ID: <?= htmlspecialchars($order_id) ?></h2>

    <!-- Payments Table -->
    <table class="table table-striped mt-4">
        <thead>
            <tr>
                <th>Payment Date</th>
                <th>Amount</th>
                <th>Method</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result): ?>
                <?php foreach ($result as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['payment_date']) ?></td>
                        <td>$<?= number_format($row['amount'], 2) ?></td>
                        <td><?= htmlspecialchars($row['method']) ?></td>
                        <td><?= htmlspecialchars($row['status']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="text-center">No payments found for this order.</td>
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
