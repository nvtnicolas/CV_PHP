<?php
// session.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'db.php';  // Include database connection as $pdo

// Check if the user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');  // Redirect to login page if not logged in
    exit();
}

// Retrieve the user ID and username from the session
$user_id = $_SESSION['user_id'] ?? null;
$username = $_SESSION['username'] ?? '';

// Check if the user has a CV and retrieve the full name
try {
    $stmt = $pdo->prepare('SELECT fullname FROM cvs WHERE user_id = :user_id');
    $stmt->execute(['user_id' => $user_id]);
    $cv = $stmt->fetch(PDO::FETCH_ASSOC);
    $display_name = $cv && !empty($cv['fullname']) ? $cv['fullname'] : 'Edit your CV in profile';
} catch (PDOException $e) {
    echo '<p style="color:red;">Error fetching CV data: ' . $e->getMessage() . '</p>';
    $display_name = 'Edit your CV in profile';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Your Custom CSS -->
    <link rel="stylesheet" href="test.css">
</head>
<body>

<?php if (!empty($username)): ?>

    <!-- Header section -->
    <header class="container-fluid bg-dark text-white py-3">
        <div class="d-flex justify-content-between align-items-center">
            <!-- Display the full name or prompt to edit CV -->
            <h1 class="h4">Welcome, <?php echo htmlspecialchars($display_name); ?>!</h1>

            <!-- Navigation menu -->
            <nav>
                <ul class="nav">
                    <li class="nav-item"><a class="nav-link text-white" href="router.php?page=index">Home</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="router.php?page=profile">Profile</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="router.php?page=project">Projects</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="router.php?page=contact">Contact</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="logout.php">Log out</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Main content -->
    <main class="container my-5">
        <h2>Welcome to the homepage!</h2>
        <p>This is the content section of your homepage. You can customize this further as needed.</p>
    </main>

    <!-- Footer section -->
    <footer class="container-fluid bg-dark text-white text-center py-3">
        <p>&copy; 2024 All rights reserved.</p>
        <p>Contact us at <a class="btn btn-light" href="contact.php">nicolas.nguyenvanthnah@ynov.com</a></p>
    </footer>

<?php endif; ?>

<!-- Bootstrap JS and dependencies (optional for Bootstrap functionality) -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
