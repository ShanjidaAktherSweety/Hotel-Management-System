<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/response.php';

try {
    $alerts = [];

    // 1. Active room service orders
    $roomServiceStmt = $pdo->query("
        SELECT COUNT(*) 
        FROM restaurant_orders
        WHERE order_type = 'Room Service'
          AND order_status IN ('Pending', 'Preparing')
    ");
    $roomServiceCount = (int)$roomServiceStmt->fetchColumn();

    if ($roomServiceCount > 0) {
        $alerts[] = [
            'icon' => 'fa-bell-concierge',
            'title' => 'Room Service Requests',
            'message' => $roomServiceCount . ' room service order(s) are currently active.'
        ];
    }

    // 2. High priority kitchen queue
    $kitchenStmt = $pdo->query("
        SELECT COUNT(*)
        FROM restaurant_orders
        WHERE kitchen_priority IN ('High', 'Urgent')
          AND order_status IN ('Pending', 'Preparing')
    ");
    $kitchenCount = (int)$kitchenStmt->fetchColumn();

    if ($kitchenCount > 0) {
        $alerts[] = [
            'icon' => 'fa-fire-burner',
            'title' => 'Kitchen Priority Queue',
            'message' => $kitchenCount . ' high-priority order(s) are still in progress.'
        ];
    }

    // 3. Billing added to room bill
    $billingStmt = $pdo->query("
        SELECT COUNT(*)
        FROM restaurant_orders
        WHERE billing_option = 'Add to Guest Room Bill'
    ");
    $billingCount = (int)$billingStmt->fetchColumn();

    if ($billingCount > 0) {
        $alerts[] = [
            'icon' => 'fa-file-invoice-dollar',
            'title' => 'Billing Added',
            'message' => $billingCount . ' order(s) were added to guest room bills.'
        ];
    }

    if (empty($alerts)) {
        $alerts[] = [
            'icon' => 'fa-circle-check',
            'title' => 'No Restaurant Alerts',
            'message' => 'Restaurant operations are running normally.'
        ];
    }

    jsonResponse(true, 'Restaurant alerts loaded successfully.', $alerts);
} catch (Exception $e) {
    jsonResponse(false, 'Failed to load restaurant alerts: ' . $e->getMessage());
}
?>