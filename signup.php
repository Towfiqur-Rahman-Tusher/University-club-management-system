<?php
include 'config.php';

$error = [];
$success = false;

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    // Sanitize inputs
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $father_name = mysqli_real_escape_string($conn, $_POST['father_name']);
    $mother_name = mysqli_real_escape_string($conn, $_POST['mother_name']);
    $student_id = mysqli_real_escape_string($conn, $_POST['student_id']);
    $semester = mysqli_real_escape_string($conn, $_POST['semester']);
    $section = mysqli_real_escape_string($conn, $_POST['section']);
    $department = mysqli_real_escape_string($conn, $_POST['department']);
    $password = md5($_POST['password']);
    $clubs = isset($_POST['clubs']) ? implode(", ", $_POST['clubs']) : '';

    // Check if user already exists
    $select = "SELECT * FROM members WHERE email = '$email' OR student_id = '$student_id'";
    $result = mysqli_query($conn, $select);

    if (mysqli_num_rows($result) > 0) {
        $error[] = 'User already exists!';
    } else {
        $insert = "INSERT INTO members (name, email, father_name, mother_name, student_id, 
                  semester, section, department, password, clubs) 
                  VALUES ('$name', '$email', '$father_name', '$mother_name', 
                  '$student_id', '$semester', '$section', '$department', '$password', '$clubs')";
        
        if (mysqli_query($conn, $insert)) {
            header('Location: login.php');
            exit();
        } else {
            $error[] = 'Registration failed: ' . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Club Membership Signup</title>
    <link rel="stylesheet" href="signup.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <!-- Header Section -->
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
                        <a class="nav-link" href="contact.php">Contact Us</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger mt-4">
                <?php foreach ($error as $msg): ?>
                    <p><?php echo $msg; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form class="club-form" method="POST">
            <h2 class="mb-4 text-center">Club Membership Registration</h2>

            <div class="mb-3">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" class="form-control" required 
                       value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '' ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required
                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label">Father's Name</label>
                    <input type="text" name="father_name" class="form-control" required
                           value="<?php echo isset($_POST['father_name']) ? htmlspecialchars($_POST['father_name']) : '' ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Mother's Name</label>
                    <input type="text" name="mother_name" class="form-control" required
                           value="<?php echo isset($_POST['mother_name']) ? htmlspecialchars($_POST['mother_name']) : '' ?>">
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label">Student ID</label>
                    <input type="text" name="student_id" class="form-control" pattern="[0-9]{16}" required
                           value="<?php echo isset($_POST['student_id']) ? htmlspecialchars($_POST['student_id']) : '' ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Semester</label>
                    <select name="semester" class="form-select" required>
                        <?php $selectedSem = $_POST['semester'] ?? ''; ?>
                        <option value="">Select</option>
                        <?php for ($i = 1; $i <= 8; $i++): ?>
                            <option value="<?php echo $i; ?>" <?php echo $selectedSem == $i ? 'selected' : '' ?>>
                                <?php echo $i . getOrdinal($i); ?> Semester
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Section</label>
                    <select name="section" class="form-select" required>
                        <?php $selectedSec = $_POST['section'] ?? ''; ?>
                        <option value="">Select</option>
                        <?php foreach (['A', 'B', 'C', 'D', 'E', 'F'] as $sec): ?>
                            <option value="<?php echo $sec; ?>" <?php echo $selectedSec == $sec ? 'selected' : '' ?>>
                                <?php echo $sec; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">Department</label>
                <select name="department" class="form-select" required>
                    <?php $selectedDept = $_POST['department'] ?? ''; ?>
                    <option value="">Select Department</option>
                    <option value="CSE" <?php echo $selectedDept == 'CSE' ? 'selected' : '' ?>>Computer Science & Engineering</option>
                    <option value="EEE" <?php echo $selectedDept == 'EEE' ? 'selected' : '' ?>>Electrical & Electronic Engineering</option>
                    <option value="LAW" <?php echo $selectedDept == 'LAW' ? 'selected' : '' ?>>Department of Law</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <div class="mb-4">
                <label class="form-label">Select Clubs</label>
                <div class="club-checkboxes">
                    <?php $selectedClubs = $_POST['clubs'] ?? []; ?>
                    <div class="checkbox-group">
                        <input type="checkbox" name="clubs[]" value="Debating Society" id="debate"
                            <?php echo in_array('Debating Society', $selectedClubs) ? 'checked' : '' ?>>
                        <label for="debate">Debating Society</label>
                    </div>
                    <div class="checkbox-group">
                        <input type="checkbox" name="clubs[]" value="Computer Club" id="computer"
                            <?php echo in_array('Computer Club', $selectedClubs) ? 'checked' : '' ?>>
                        <label for="computer">Computer Club</label>
                    </div>
                    <div class="checkbox-group">
                        <input type="checkbox" name="clubs[]" value="Robotics Club" id="robotics"
                            <?php echo in_array('Robotics Club', $selectedClubs) ? 'checked' : '' ?>>
                        <label for="robotics">Robotics Club</label>
                    </div>
                </div>
            </div>

            <button type="submit" name="submit" class="btn btn-primary submit-btn">Join Selected Clubs</button>
        </form>
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
            <p>&copy; 2023 Premier University Club Management System. All rights reserved</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
function getOrdinal($number) {
    $ends = array('th','st','nd','rd','th','th','th','th','th','th');
    if (($number %100) >= 11 && ($number%100) <= 13)
        return 'th';
    else
        return $ends[$number % 10];
}
?>