<?php
require_once __DIR__ . '/auth_check.php';

function requireRole($allowedRoles) {
    global $currentUser;

    if (!in_array($currentUser['role'], $allowedRoles)) {
        header("Location: dashboard.php");
        exit();
    }
}
?>