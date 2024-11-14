<?php
// index.php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ordering Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"> <!-- Google Icons -->
    <style>
        body {
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .navbar {
            background-color: #333;
            color: white;
            padding: 15px;
        }

        .navbar a,
        .navbar .icon-button {
            color: white;
            text-decoration: none;
            padding: 10px;
        }

        .navbar .menu-icon {
            display: none;
        }

        .navbar .icon-button:hover {
            background-color: #555;
            border-radius: 5px;
        }

        /* Dark mode styling */
        body.dark-mode {
            background-color: #121212;
            color: white;
        }

        body.dark-mode .navbar {
            background-color: #222;
        }

        /* Modal styling */
        .modal-content {
            padding: 20px;
            border-radius: 5px;
        }

        .dark-mode-toggle-btn {
            cursor: pointer;
            background-color: #f39c12;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
        }

        .dark-mode-toggle-btn:hover {
            background-color: #e67e22;
        }

        .right-nav {
            margin-left: auto;
        }

        /* Search Bar Modal */
        .search-bar-btn {
            cursor: pointer;
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Ordering Management System</a>

            <button class="navbar-toggler menu-icon" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <!-- Dashboard Icon -->
                    <li class="nav-item">
                        <a href="dashboard.php" class="nav-link icon-button">
                            <span class="material-icons">dashboard</span> Dashboard
                        </a>
                    </li>

                    <!-- Search Icon with Modal -->
                    <li class="nav-item">
                        <a href="#" class="nav-link icon-button search-bar-btn" data-bs-toggle="modal" data-bs-target="#searchModal">
                            <span class="material-icons">search</span> Search
                        </a>
                    </li>

                    <!-- Dark Mode Toggle Button -->
                    <li class="nav-item">
                        <button class="dark-mode-toggle-btn" id="dark-mode-toggle">Toggle Dark Mode</button>
                    </li>

                    <!-- Right side login/logout buttons -->
                    <div class="right-nav">
                        <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) { ?>
                            <!-- Logout Icon -->
                            <li class="nav-item">
                                <a href="#" class="nav-link icon-button" id="logout-btn">
                                    <span class="material-icons">exit_to_app</span> Logout
                                </a>
                            </li>
                        <?php } else { ?>
                            <!-- Login Icon -->
                            <li class="nav-item">
                                <a href="login.php" class="nav-link icon-button">
                                    <span class="material-icons">login</span> Login
                                </a>
                            </li>
                        <?php } ?>
                    </div>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Modal for Search Bar -->
    <div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="searchModalLabel">Search</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="text" class="form-control" placeholder="Search...">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Search</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <div class="container mt-5">
        <h1>Welcome to the Ordering Management System</h1>

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

    <script>
        // Dark Mode Toggle
        const darkModeToggle = document.getElementById('dark-mode-toggle');
        darkModeToggle.addEventListener('click', () => {
            document.body.classList.toggle('dark-mode');
        });

        // Logout Modal
        const logoutBtn = document.getElementById('logout-btn');
        logoutBtn.addEventListener('click', () => {
            if (confirm('Are you sure you want to logout?')) {
                window.location.href = 'logout.php'; // Redirect to logout page
            }
        });
    </script>

</body>

</html>
