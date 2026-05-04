<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/response.php';

try {
    $stmt = $pdo->prepare("
        SELECT item_name, quantity, minimum_stock
        FROM inventory
        WHERE category = 'Activity Equipment'
        ORDER BY item_name ASC
    ");

    $stmt->execute();
    $equipment = $stmt->fetchAll(PDO::FETCH_ASSOC);

    jsonResponse(true, 'Activity equipment loaded from inventory successfully.', $equipment);
} catch (Exception $e) {
    jsonResponse(false, 'Failed to load activity equipment: ' . $e->getMessage());
}
?>