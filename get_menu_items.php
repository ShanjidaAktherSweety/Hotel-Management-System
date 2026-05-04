<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/response.php';

try {
    $stmt = $pdo->query("
        SELECT id, item_name, category, price, description, image_path, item_status
        FROM restaurant_menu_items
        WHERE item_status = 'Available'
        ORDER BY id DESC
    ");

    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    jsonResponse(true, 'Menu items fetched successfully.', $items);
} catch (Exception $e) {
    jsonResponse(false, 'Failed to fetch menu items: ' . $e->getMessage());
}
?>