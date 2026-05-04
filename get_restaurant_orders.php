<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/response.php';

try {
    $stmt = $pdo->query("
        SELECT order_id, guest_name, room_table_ref, order_type, menu_item, order_status, billing_option
        FROM restaurant_orders
        ORDER BY id DESC
        LIMIT 20
    ");

    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    jsonResponse(true, 'Restaurant orders fetched successfully.', $orders);
} catch (Exception $e) {
    jsonResponse(false, 'Failed to fetch restaurant orders: ' . $e->getMessage());
}
?>