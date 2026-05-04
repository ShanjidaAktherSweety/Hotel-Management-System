<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/response.php';

try {
    $stmt = $pdo->query("
        SELECT
            menu_item,
            COUNT(*) AS total_orders
        FROM restaurant_orders
        WHERE menu_item IS NOT NULL
          AND menu_item != ''
        GROUP BY menu_item
        ORDER BY total_orders DESC, menu_item ASC
        LIMIT 5
    ");

    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    jsonResponse(true, 'Top selling items loaded successfully.', $items);
} catch (Exception $e) {
    jsonResponse(false, 'Failed to load top selling items: ' . $e->getMessage());
}
?>