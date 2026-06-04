<?php
session_start();

if (!isset($_SESSION['user'])) {
    $depth = substr_count($_SERVER['PHP_SELF'], '/') - 2;
    $login_url = str_repeat('../', $depth - 1) . 'login.php';
    header("Location: $login_url");
    exit;
}

function isAdmin() {
    return $_SESSION['role'] === 'admin';
}

function isStudent() {
    return $_SESSION['role'] === 'student';
}

function requireAdmin() {
    if (!isAdmin()) {
        die("Access denied. Admins only.");
    }
}
?>
