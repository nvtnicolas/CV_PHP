<?php
// Include your database connection file
include 'db.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Retrieve the username from the session
$username = $_SESSION['username'] ?? '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize the input data to avoid XSS or code injection
    $name = htmlspecialchars(trim($_POST['name']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $message = htmlspecialchars(trim($_POST['message']));

    // Validate email format
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        try {
            // Insert contact data into the 'contacts' table
            $stmt = $pdo->prepare('INSERT INTO contacts (name, email, message) VALUES (:name, :email, :message)');
            $stmt->execute([
                'name' => $name,
                'email' => $email,
                'message' => $message
            ]);
            echo '<div class="alert alert-success" role="alert">Message sent successfully!</div>';
        } catch (PDOException $e) {
            echo '<div class="alert alert-danger" role="alert">Error saving message: ' . $e->getMessage() . '</div>';
        }
    } else {
        echo '<div class="alert alert-danger" role="alert">Invalid email address.</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="test.css">
    <!-- Google Maps API -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBy5f0XGuQoEQfuV42q1ty4fKZ5LNEXHh4"></script>
    <script>
        // Initialize and add the map
        function initMap() {
            const cityLocation = { lat: 43.6103201, lng: 1.4310661 }; // Replace with your city coordinates
            const map = new google.maps.Map(document.getElementById('map'), {
                zoom: 12,
                center: cityLocation
            });
            const marker = new google.maps.Marker({
                position: cityLocation,
                map: map
            });
        }
        window.onload = initMap;
    </script>
</head>
<body>
<header class="container-fluid bg-dark text-white py-3">
    <div class="d-flex justify-content-between align-items-center">
        <form method="GET" action="router.php" class="form-inline">
            <button class="btn btn-primary mx-1" name="page" value="index">Home</button>
            <button class="btn btn-primary mx-1" name="page" value="profile">Profile</button>
            <button class="btn btn-primary mx-1" name="page" value="project">Projects</button>
            <button class="btn btn-primary mx-1" name="page" value="contact">Contact</button>
        </form>
        <?php if (!empty($username)): ?>
            <span>Contact support: <?php echo htmlspecialchars($username); ?></span>
        <?php endif; ?>
        <a href="logout.php" class="btn btn-danger">Log out</a>
    </div>
</header>

<main class="container my-5">
    <h2>Contact Admin</h2>
    <form method="POST" action="">
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="message">Message:</label>
            <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
        </div>
        <button type="submit" class="btn btn-success">Send</button>
    </form>

    <h3>Your City Location</h3>
    <div id="map" style="height: 400px; width: 100%;"></div>
</main>

<footer class="container-fluid bg-dark text-white text-center py-3">
    <p>&copy; 2024 All rights reserved.</p>
    <p>Contact us at <a class="btn btn-light" href="contact.php">nicolas.nguyenvanthnah@ynov.com</a></p>
</footer>

<!-- Bootstrap JS and dependencies (optional for Bootstrap functionality) -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
