<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Database Connection
$conn = new mysqli("localhost", "root", "", "club_members");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user data securely
$user_stmt = $conn->prepare("SELECT * FROM members WHERE id = ?");
$user_stmt->bind_param("i", $_SESSION['user_id']);
$user_stmt->execute();
$user = $user_stmt->get_result()->fetch_assoc();

// Fetch club details securely
$clubs = !empty($user['clubs']) ? explode(", ", $user['clubs']) : [];

$notifications = [];
if (!empty($clubs)) {
    $placeholders = implode(',', array_fill(0, count($clubs), '?')); 
    $types = str_repeat('s', count($clubs)); 

    $notif_stmt = $conn->prepare("SELECT * FROM notifications WHERE club_name IN ($placeholders) ORDER BY created_at DESC LIMIT 5");
    $notif_stmt->bind_param($types, ...$clubs);
    $notif_stmt->execute();
    $notifications = $notif_stmt->get_result();
}

// Mark notification as read
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['mark_read'])) {
    $update_stmt = $conn->prepare("UPDATE notifications SET is_read = 1 WHERE user_id = ?");
    $update_stmt->bind_param("i", $_SESSION['user_id']);
    $update_stmt->execute();
    header("Location: profile.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University Club Management</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Header Section -->
    <header class="header">
        <div class="container-fluid d-flex align-items-center">
            <img src="LOGO.png" alt="University Logo" class="logo">
            <h1 class="title-text">Premier University Club Management System</h1>
            <a href="logout.php" class="btn btn-outline-danger ms-auto">Sign Out</a>
        </div>
    </header>
    
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg bg-light">
        <div class="container">
            <span class="navbar-brand">Welcome, <?php echo htmlspecialchars($user['name']); ?></span>
        </div>
    </nav>
    
    <div class="container mt-4">
        <div class="row">
            <!-- Notifications -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <span>Notifications</span>
                        <span class="badge bg-primary"> <?php echo $notifications->num_rows; ?> </span>
                    </div>
                    <div class="card-body">
                        <?php if ($notifications->num_rows > 0): ?>
                            <?php while ($notif = $notifications->fetch_assoc()): ?>
                                <div class="alert alert-<?php echo $notif['is_read'] ? 'light' : 'info'; ?>">
                                    <strong><?php echo htmlspecialchars($notif['club_name']); ?>:</strong>
                                    <?php echo htmlspecialchars($notif['message']); ?>
                                    <?php if (!empty($notif['link'])): ?>
                                        <a href="<?php echo htmlspecialchars($notif['link']); ?>" class="btn btn-sm btn-primary">View</a>
                                    <?php endif; ?>
                                    <small class="text-muted d-block"> <?php echo $notif['created_at']; ?> </small>
                                </div>
                            <?php endwhile; ?>
                            <form method="POST">
                                <button type="submit" name="mark_read" class="btn btn-sm btn-secondary">Mark All as Read</button>
                            </form>
                        <?php else: ?>
                            <p class="text-muted">No notifications.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Club Details -->
            <div class="col-md-8">
                <?php if (!empty($clubs)): ?>
                    <?php foreach ($clubs as $club): ?>
                        <div class="club-card p-3 mb-3 border rounded">
                            <h4 class="mb-2"> <?php echo htmlspecialchars($club); ?> </h4>
                            <p class="text-muted">Upcoming event on [Date]</p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-muted">You are not a member of any club.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
