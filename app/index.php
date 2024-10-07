<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');  // Redirect to login if not logged in
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<header>
    <h1>Welcome, <?php echo $_SESSION['username']; ?>!</h1>
    <form method="GET" action="router.php">
        <button class="nav-btn" name="page" value="admin">Admin</button>
        <button class="nav-btn" name="page" value="profile">Profile</button>
        <button class="nav-btn" name="page" value="project">Projects</button>
        <button class="nav-btn" name="page" value="contact">Contact</button>
    </form>
    <a href="logout.php">Log out</a> <!-- Log out link -->
</header>

</body>
</html>
