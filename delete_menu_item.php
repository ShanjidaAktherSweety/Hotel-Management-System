<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/response.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(false, 'Invalid request method.');
}

$id = (int)($_POST['id'] ?? 0);

if ($id <= 0) {
    jsonResponse(false, 'Invalid menu item ID.');
}

try {
    $stmt = $pdo->prepare("DELETE FROM restaurant_menu_items WHERE id = :id");
    $stmt->execute([':id' => $id]);

    jsonResponse(true, 'Menu item deleted successfully.');
} catch (Exception $e) {
    jsonResponse(false, 'Failed to delete menu item: ' . $e->getMessage());
}
?>