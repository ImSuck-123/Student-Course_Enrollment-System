<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
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
