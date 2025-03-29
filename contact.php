<?php
include 'config.php';

$error = "";
$success = "";

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Form submission handling
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate inputs
    $name = trim(htmlspecialchars($_POST['name']));
    $email = trim(htmlspecialchars($_POST['email']));
    $subject = trim(htmlspecialchars($_POST['subject']));
    $message = trim(htmlspecialchars($_POST['message']));
    
    // Basic validation
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $error = "All fields are required!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format!";
    } else {
        // Prepare and bind
        $stmt = $conn->prepare("INSERT INTO contacts (name, email, subject, message) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $subject, $message);
        
        // Execute the statement
        if ($stmt->execute()) {
            $success = "Message sent successfully!";
            $_POST = array(); // Reset form inputs
        } else {
            $error = "Error: " . $stmt->error;
        }
        
        $stmt->close();
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact us</title>
    <link rel="stylesheet" href="contact.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

</head>
<body>
    <!-- Header Section -->
    <header class="header">
        <div class="container-fluid">
            <div class="d-flex align-items-center">
                <!-- Logo -->
                <img src="LOGO.png" alt="University Logo" class="logo">
                
                <!-- Title -->
                <h1 class="title-text">Premier University Club Management System</h1>
                
                <!-- Auth Buttons -->
                <div class="auth-buttons ms-auto">
                    <a href="login.php" class="btn btn-outline-primary">Login</a>
                    <a href="signup.php" class="btn btn-primary">Sign Up</a>
                </div>
            </div>
        </div>
    </header>

    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg nav-bar">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.html">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="aboutus.html">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="gallery.html">Gallery</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Contact Us</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="contact-container">
        <div class="contact-header">
            <h1>Contact Us</h1>
            <p>We'd love to hear from you! Please fill out the form below.</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="error-message"> <?php echo $error; ?> </div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="success-message"> <?php echo $success; ?> </div>
        <?php endif; ?>

        <div class="contact-content">
            <form class="contact-form" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="form-group">
                    <label for="name">Your Name:</label>
                    <input type="text" id="name" name="name" value="<?php echo $_POST['name'] ?? ''; ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">Your Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo $_POST['email'] ?? ''; ?>" required>
                </div>

                <div class="form-group">
                    <label for="subject">Subject:</label>
                    <select id="subject" name="subject" required>
                        <option value="">Select a subject</option>
                        <option value="feedback" <?php echo ($_POST['subject'] ?? '') == 'feedback' ? 'selected' : ''; ?>>General Feedback</option>
                        <option value="support" <?php echo ($_POST['subject'] ?? '') == 'support' ? 'selected' : ''; ?>>Technical Support</option>
                        <option value="suggestion" <?php echo ($_POST['subject'] ?? '') == 'suggestion' ? 'selected' : ''; ?>>Feature Suggestion</option>
                        <option value="other" <?php echo ($_POST['subject'] ?? '') == 'other' ? 'selected' : ''; ?>>Other</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="message">Your Message:</label>
                    <textarea id="message" name="message" required><?php echo $_POST['message'] ?? ''; ?></textarea>
                </div>

                <button type="submit">Send Message</button>
            </form>

            <div class="contact-info">
                <div class="info-item">
                    <h3>Contact Information</h3>
                    <p>Email: contact@project.com</p>
                    <p>Phone: +1 (555) 123-4567</p>
                </div>
                <div class="info-item">
                    <h3>Office Address</h3>
                    <p>123 Tech Street, Silicon Valley, CA 94043, United States</p>
                </div>
                <div class="info-item">
                    <h3>Working Hours</h3>
                    <p>Monday - Friday: 9 AM - 5 PM (PST)</p>
                </div>
            </div>
        </div>
    </div>

            <!-- Footer -->
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
                <p>&copy; 2023 Premier University Club Management System
                    . All rights reserved</p>
            </div>
        </footer>
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
</body>
</html>


