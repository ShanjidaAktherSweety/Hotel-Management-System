<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/response.php';

try {
    $totalOrders = (int)$pdo->query("SELECT COUNT(*) FROM restaurant_orders")->fetchColumn();

    $roomService = (int)$pdo->query("
        SELECT COUNT(*) 
        FROM restaurant_orders 
        WHERE order_type = 'Room Service'
    ")->fetchColumn();

    $kitchenQueue = (int)$pdo->query("
        SELECT COUNT(*)
        FROM restaurant_orders
        WHERE order_status IN ('Pending', 'Preparing')
    ")->fetchColumn();

    $restaurantRevenue = (float)$pdo->query("
        SELECT COALESCE(SUM(item_charge), 0)
        FROM restaurant_orders
    ")->fetchColumn();

    jsonResponse(true, 'Restaurant stats loaded successfully.', [
        'total_orders' => $totalOrders,
        'room_service' => $roomService,
        'kitchen_queue' => $kitchenQueue,
        'restaurant_revenue' => $restaurantRevenue
    ]);
} catch (Exception $e) {
    jsonResponse(false, 'Failed to load restaurant stats: ' . $e->getMessage());
}
?>