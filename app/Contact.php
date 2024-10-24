<?php

include './db/db.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


$username = $_SESSION['username'] ?? '';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = htmlspecialchars(trim($_POST['name']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $message = htmlspecialchars(trim($_POST['message']));


    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        try {

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

    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="assets/css/test.css">

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBy5f0XGuQoEQfuV42q1ty4fKZ5LNEXHh4"></script>
    <script>

        function initMap() {
            const cityLocation = { lat: 43.6103201, lng: 1.4310661 };
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
        <div class="d-flex align-items-center">
            <button id="theme-toggle" class="btn btn-light me-2">Dark Mode</button>
            <?php if (!empty($username)): ?>
                <span>Contact support: <?php echo htmlspecialchars($username); ?></span>
            <?php endif; ?>
            <a href="auth/logout.php" class="btn btn-danger">Log out</a>
        </div>
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
    <p>© 2024 All rights reserved.</p>
    <p>Contact us at <a class="btn btn-light" href="contact.php">nicolas.nguyenvanthnah@ynov.com</a></p>
</footer>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
<script>

    document.getElementById('theme-toggle').addEventListener('click', function() {
        document.body.classList.toggle('dark-mode');
        const buttons = document.querySelectorAll('button, .nav-btn');
        buttons.forEach(button => button.classList.toggle('dark-mode'));

        // Change button text based on the current theme
        if (document.body.classList.contains('dark-mode')) {
            this.textContent = 'Light Mode';
            this.classList.remove('btn-light');
            this.classList.add('btn-dark');
        } else {
            this.textContent = 'Dark Mode';
            this.classList.remove('btn-dark');
            this.classList.add('btn-light');
        }
    });
</script>
</body>
</html>
