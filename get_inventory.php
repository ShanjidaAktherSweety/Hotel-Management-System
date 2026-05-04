<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/response.php';

try {
    $stmt = $pdo->query("
        SELECT *
        FROM inventory
        ORDER BY id DESC
    ");
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    jsonResponse(true, 'Inventory items fetched successfully.', $items);
} catch (Exception $e) {
    jsonResponse(false, 'Failed to fetch inventory items: ' . $e->getMessage());
}
?>