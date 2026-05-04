<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: staff-login.php");
    exit();
}

$currentUser = [
    'id' => $_SESSION['user_id'] ?? null,
    'full_name' => $_SESSION['full_name'] ?? '',
    'username' => $_SESSION['username'] ?? '',
    'role' => $_SESSION['role'] ?? ''
];
?>