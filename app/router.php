<?php
session_start();


if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {

    header('Location: Index_no_log.php');
    exit();
}


if (isset($_GET['page'])) {
    $page = $_GET['page'];


    $allowedPages = ['/app/index','/admin/admin', '/user/profile', 'project', 'Contact'];


    if (in_array($page, $allowedPages)) {
        include $page . '.php';
    } else {

        echo "<h1>Error 404: Page not found</h1>";
    }
} else {

    echo "<h1>Welcome, please select a page.</h1>";
}
?>
