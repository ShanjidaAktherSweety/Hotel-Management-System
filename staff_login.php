<?php
session_start();

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/response.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(false, 'Invalid request method.');
}

$login = trim($_POST['login'] ?? '');
$password = trim($_POST['password'] ?? '');

if ($login === '' || $password === '') {
    jsonResponse(false, 'Please enter your username/email and password.');
}

try {
    $sql = "SELECT * FROM users 
            WHERE (username = :login OR email = :login) 
            AND status = 'Active' 
            LIMIT 1";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':login' => $login]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        jsonResponse(false, 'User not found or inactive.');
    }

    if (!password_verify($password, $user['password_hash'])) {
        jsonResponse(false, 'Incorrect password.');
    }

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['full_name'] = $user['full_name'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['role'] = $user['role'];

    jsonResponse(true, 'Login successful.', [
        'redirect' => 'dashboard.php'
    ]);
} catch (Exception $e) {
    jsonResponse(false, 'Login failed: ' . $e->getMessage());
}
?>