<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: Index_no_log.php');
    exit();
}

if (isset($_GET['page'])) {
    $page = $_GET['page'];

    // Mapping logical page names to their file paths
    $allowedPages = [
        'index' => 'index',        // Correct path is app/index.php
        'profile' => 'user/profile',
        'project' => 'project',
        'contact' => 'Contact',    // Case-sensitive on some systems
        'admin' => 'admin/admin'   // Add the admin page
    ];

    // Check if the requested page is in the allowedPages
    if (array_key_exists($page, $allowedPages)) {
        // Include the appropriate file based on the page requested
        include $allowedPages[$page] . '.php';
    } else {
        echo "<h1>Error 404: Page not found</h1>";
    }
} else {
    echo "<h1>Welcome, please select a page.</h1>";
}
?>