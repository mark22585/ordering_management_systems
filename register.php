<?php 
// register.php

// Include the database connection file
include('db.connection.php');

// Check if the registration form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Capture user inputs
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Insert user data into the database
    $sql = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
    
    // Prepare the statement
    $stmt = $pdo->prepare($sql);

    try {
        // Execute the query with the provided data
        $stmt->execute(['username' => $username, 'email' => $email, 'password' => $password]);

        // Redirect to login page after successful registration
        header("Location: login.php?registration=success");
        exit();
    } catch (Exception $e) {
        // Handle errors if any
        $error = "Error: " . $e->getMessage();
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js"></script> <!-- FontAwesome for icons -->
    
    <style>
        body {
            background: linear-gradient(45deg, #333, #111);
            animation: bgChange 5s ease-in-out infinite alternate;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Arial', sans-serif;
        }

        @keyframes bgChange {
            0% {
                background: #333;
            }
            100% {
                background: #111;
            }
        }

        .modal-content {
            background-color: #222;
            color: white;
            border-radius: 8px;
            animation: fadeIn 1s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .input-group-text i {
            color: black;
        }

        .input-group .form-control {
            border-radius: 5px;
        }

        .modal-header .modal-title {
            color: white;
            font-size: 1.5rem;
        }

        .btn-primary {
            background-color: #4CAF50;
            border-color: #45a049;
        }

        .btn-primary:hover {
            background-color: #45a049;
        }

        .container {
            max-width: 500px;
            margin-top: 40px;
        }

        .form-label {
            color: white;
        }

        .form-control {
            background-color: #333;
            color: white;
            border: 1px solid #444;
        }

        .form-control:focus {
            background-color: #444;
            border-color: #4CAF50;
        }

        .error {
            color: red;
            text-align: center;
        }

        .terms-link {
            color: #ccc;
        }

        .terms-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<!-- Register Modal -->
<div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="registerModalLabel"><i class="fas fa-user-plus"></i> Create an Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>

                <form action="register.php" method="POST">
                    <div class="mb-3 input-group">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                        <input type="text" class="form-control" name="username" placeholder="Username" required>
                    </div>
                    <div class="mb-3 input-group">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <input type="email" class="form-control" name="email" placeholder="Email" required>
                    </div>
                    <div class="mb-3 input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" class="form-control" name="password" placeholder="Password" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Register</button>
                </form>

                <div class="mt-3 text-center">
                    <p>Already have an account? <a href="login.php" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#loginModal">Login here</a>.</p>
                </div>

                <div class="mt-2 text-center">
                    <a href="#" class="terms-link">Terms & Conditions</a> | <a href="#" class="terms-link">Privacy Policy</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Login (Trigger if user has already registered) -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loginModalLabel"><i class="fas fa-sign-in-alt"></i> Login</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Your account has been successfully created! Please log in to continue.</p>
                <a href="login.php" class="btn btn-primary w-100">Go to Login</a>
            </div>
        </div>
    </div>
</div>

<!-- Button to trigger register modal -->
<div class="container mt-5">
    <div class="d-flex justify-content-center">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#registerModal">
            Register Now
        </button>
    </div>
</div>

<!-- Bootstrap JS and Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>

<script>
    // Automatically show the register modal when the page loads
    window.onload = function() {
        new bootstrap.Modal(document.getElementById('registerModal')).show();
    };
</script>

</body>
</html>
