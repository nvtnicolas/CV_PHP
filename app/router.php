<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // Redirect to the login page if not logged in
    header('Location: login.php');
    exit();
}

// Check if a page parameter was provided
if (isset($_GET['page'])) {
    $page = $_GET['page'];

    // Define the list of allowed pages
    $allowedPages = ['admin', 'profile', 'project', 'contact'];

    // If the page is allowed, include it
    if (in_array($page, $allowedPages)) {
        include $page . '.php';
    } else {
        // If the page is not allowed, show a 404 error
        echo "<h1>Error 404: Page not found</h1>";
    }
} else {
    // If no page is provided, show a default message
    echo "<h1>Welcome, please select a page.</h1>";
}
?>
