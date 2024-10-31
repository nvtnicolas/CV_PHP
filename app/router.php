<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: Index_no_log.php');
    exit();
}

if (isset($_GET['page'])) {
    $page = $_GET['page'];


    $allowedPages = [
        'index' => 'index',
        'profile' => 'user/profile',
        'project' => 'project',
        'contact' => 'Contact',
        'admin' => 'admin/admin'
    ];


    if (array_key_exists($page, $allowedPages)) {
        include $allowedPages[$page] . '.php';
    } else {
        echo "<h1>Error 404: Page not found</h1>";
    }
} else {
    echo "<h1>Welcome, please select a page.</h1>";
}
?>