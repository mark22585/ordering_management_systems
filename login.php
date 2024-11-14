<?php
session_start();
include('db.connection.php');

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Fetch user data based on email
    $sql = "SELECT * FROM users WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verify password and start session if valid
    if ($user && password_verify($password, $user['password'])) {
        // Set session variables
        $_SESSION['loggedin'] = true;
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];

        // Debugging: Check if session is properly set
        // var_dump($_SESSION); // Uncomment only for debugging

        // Redirect to dashboard
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid email or password.";
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js"></script> <!-- FontAwesome for icons -->
    <style>
        body {
            background: linear-gradient(45deg, #4CAF50, #111);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Arial', sans-serif;
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

<!-- Login Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loginModalLabel"><i class="fas fa-user-circle"></i> Login</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>

                <form action="login.php" method="POST">
                    <!-- Email field with icon -->
                    <div class="mb-3 input-group">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                    </div>
                    <!-- Password field with icon -->
                    <div class="mb-3 input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </form>

                <!-- Forgot Password link -->
                <p class="mt-3"><a href="#" data-bs-toggle="modal" data-bs-target="#forgotPasswordModal">Forgot Password?</a></p>

                <!-- Additional links: Terms, Privacy, Sign up -->
                <div class="mt-3 text-center">
                    <p><a href="terms.php" target="_blank">Terms of Service</a> | <a href="privacy.php" target="_blank">Privacy Policy</a></p>
                    <p>Don't have an account? <a href="register.php">Sign up here</a>.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Forgot Password Modal -->
<div class="modal fade" id="forgotPasswordModal" tabindex="-1" aria-labelledby="forgotPasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="forgotPasswordModalLabel">Forgot Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="forgot-password.php" method="POST">
                    <div class="mb-3">
                        <label for="email" class="form-label">Enter your email to reset your password</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <button type="submit" class="btn btn-warning w-100">Reset Password</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Button to trigger modal -->
<div class="container mt-5">
    <div class="d-flex justify-content-center">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#loginModal">
            Login
        </button>
    </div>
</div>

<!-- Bootstrap JS and Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
<script>
    // Automatically show login modal when page loads
    window.onload = function() {
        new bootstrap.Modal(document.getElementById('loginModal')).show();
    };
</script>

</body>
</html>
