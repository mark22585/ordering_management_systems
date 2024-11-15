<?php
include('db.connection.php');  // Include the database connection

// Insert Order
if (isset($_POST['add_order'])) {
    $customer_id = $_POST['customer_id'];
    $total = $_POST['total'];

    // Use PDO to insert data
    $query = "INSERT INTO Orders (customer_id, total) VALUES (:customer_id, :total)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':customer_id', $customer_id);
    $stmt->bindParam(':total', $total);

    try {
        $stmt->execute();
        echo "New order added successfully!";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Delete Order
if (isset($_GET['delete_order'])) {
    $order_id = $_GET['delete_order'];
    $query = "DELETE FROM Orders WHERE id=:id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $order_id);

    try {
        $stmt->execute();
        echo "Order deleted successfully!";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Get all orders
$query = "SELECT * FROM Orders";
$stmt = $pdo->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>

<div class="container mt-4">
    <h2>Order Management</h2>

    <!-- Button to Add Order -->
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addOrderModal">Add Order</button>

    <!-- Button to open the Back to Dashboard modal -->
    <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#backToDashboardModal">
        <span class="material-icons">arrow_back</span> Back to Dashboard
    </button>

    <!-- Orders Table -->
    <table class="table table-striped mt-4">
        <thead>
            <tr>
                <th>ID</th>
                <th>Customer</th>
                <th>Order Date</th>
                <th>Total</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['customer_id'] ?></td>
                    <td><?= $row['order_date'] ?></td>
                    <td>$<?= number_format($row['total'], 2) ?></td>
                    <td><?= $row['status'] ?></td>
                    <td>
                        <a href="?delete_order=<?= $row['id'] ?>" class="btn btn-danger btn-sm">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Add Order Modal -->
<div class="modal fade" id="addOrderModal" tabindex="-1" aria-labelledby="addOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addOrderModalLabel">Add New Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="orders.php" method="POST">
                    <div class="mb-3">
                        <label for="customer_id" class="form-label">Customer ID</label>
                        <input type="number" class="form-control" name="customer_id" required>
                    </div>
                    <div class="mb-3">
                        <label for="total" class="form-label">Total Amount</label>
                        <input type="number" step="0.01" class="form-control" name="total" required>
                    </div>
                    <button type="submit" name="add_order" class="btn btn-primary">Add Order</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Back to Dashboard Modal -->
<div class="modal fade" id="backToDashboardModal" tabindex="-1" aria-labelledby="backToDashboardModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="backToDashboardModalLabel">Back to Dashboard</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to go back to the Dashboard?</p>
            </div>
            <div class="modal-footer">
                <a href="dashboard.php" class="btn btn-primary">Go to Dashboard</a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS and Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>

</body>
</html>
