<?php
include 'config.php';

$error = [];
$success = false;

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['email'], $_POST['password'])) {
        // Form data sanitization
        $email = $conn->real_escape_string($_POST['email']);
        $password = $_POST['password']; // Raw password for MD5 hash comparison

        // Prepare statement to fetch user data securely
        $stmt = $conn->prepare("SELECT id, name, password, clubs FROM members WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            // Check the password (MD5 hash comparison)
            if (md5($password) === $user['password']) {
                // Start the session and set session variables
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['clubs'] = !empty($user['clubs']) ? explode(", ", $user['clubs']) : [];

                // Redirect to profile page
                header("Location: profile.php");
                exit();
            } else {
                echo "<script>alert('Incorrect password!'); window.location.href='login.php';</script>";
            }
        } else {
            echo "<script>alert('User not found!'); window.location.href='login.php';</script>";
        }
    } else {
        echo "<script>alert('Please fill in all fields.'); window.location.href='login.php';</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Club Login</title>
    <link rel="stylesheet" href="signup.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header class="header">
        <div class="container-fluid">
            <div class="d-flex align-items-center">
                <img src="LOGO.png" alt="University Logo" class="logo">
                <h1 class="title-text">Premier University Club Management System</h1>
                <div class="auth-buttons ms-auto">
                    <a href="login.php" class="btn btn-outline-primary">Login</a>
                    <a href="signup.php" class="btn btn-primary">Sign Up</a>
                </div>
            </div>
        </div>
    </header>

    <nav class="navbar navbar-expand-lg nav-bar">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link active" href="index.html">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="aboutus.html">About Us</a></li>
                    <li class="nav-item"><a class="nav-link" href="gallery.html">Gallery</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact.php">Contact Us</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="login-container">
            <h2 class="text-center mb-4">Club Login</h2>
            <form action="login.php" method="POST">
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-4">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>
        </div>
    </div>

    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h4>Contact Us</h4>
                <p><i class="fas fa-envelope"></i> info@pucms.com</p>
                <p><i class="fas fa-phone"></i> +123 456 7890</p>
            </div>
            <div class="footer-section">
                <h4>Follow Us</h4>
                <div class="social-links">
                    <a href="#"><i class="fab fa-facebook"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2023 Premier University Club Management System. All rights reserved</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
</body>
</html>
