<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/response.php';

try {
    $stmt = $pdo->query("
        SELECT item_name, category, quantity, minimum_stock
        FROM inventory
        WHERE quantity <= minimum_stock
        ORDER BY quantity ASC, item_name ASC
        LIMIT 5
    ");

    $alerts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    jsonResponse(true, 'Inventory alerts loaded successfully.', $alerts);
} catch (Exception $e) {
    jsonResponse(false, 'Failed to load inventory alerts: ' . $e->getMessage());
}
?>