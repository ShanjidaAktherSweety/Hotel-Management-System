<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/response.php';

try {
    $notifications = [];

    // 1. Inventory low stock / out of stock
    $inventoryStmt = $pdo->query("
        SELECT item_name, category, quantity, minimum_stock
        FROM inventory
        WHERE quantity <= minimum_stock
        ORDER BY quantity ASC, item_name ASC
        LIMIT 5
    ");
    $inventoryItems = $inventoryStmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($inventoryItems as $item) {
        $title = $item['item_name'] . ' Low Stock';
        $message = "Current quantity is {$item['quantity']}, minimum required is {$item['minimum_stock']}.";
        $icon = 'fa-triangle-exclamation';

        if ((int)$item['quantity'] <= 0) {
            $title = $item['item_name'] . ' Out of Stock';
            $message = "{$item['category']} inventory is unavailable and needs urgent restocking.";
        }

        $notifications[] = [
            'icon' => $icon,
            'title' => $title,
            'message' => $message,
            'type' => 'inventory'
        ];
    }

    // 2. Dirty rooms
    $dirtyStmt = $pdo->query("
        SELECT room_number
        FROM rooms
        WHERE cleaning_status = 'Dirty'
        ORDER BY room_number ASC
        LIMIT 5
    ");
    $dirtyRooms = $dirtyStmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($dirtyRooms as $room) {
        $notifications[] = [
            'icon' => 'fa-broom',
            'title' => "Room {$room['room_number']} Needs Cleaning",
            'message' => "This room is dirty and waiting for housekeeping service.",
            'type' => 'housekeeping'
        ];
    }

    // 3. Maintenance alerts
    $maintenanceStmt = $pdo->query("
        SELECT room_number
        FROM rooms
        WHERE maintenance_status = 'Repair Needed'
        ORDER BY room_number ASC
        LIMIT 5
    ");
    $maintenanceRooms = $maintenanceStmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($maintenanceRooms as $room) {
        $notifications[] = [
            'icon' => 'fa-screwdriver-wrench',
            'title' => "Room {$room['room_number']} Needs Maintenance",
            'message' => "Maintenance attention is required before this room can be fully used.",
            'type' => 'maintenance'
        ];
    }

    // 4. Pending bills
    $pendingBills = (int)$pdo->query("
        SELECT COUNT(*)
        FROM bills
        WHERE invoice_status = 'Pending'
    ")->fetchColumn();

    if ($pendingBills > 0) {
        $notifications[] = [
            'icon' => 'fa-credit-card',
            'title' => 'Pending Bill Alert',
            'message' => "{$pendingBills} pending bill(s) need payment follow-up.",
            'type' => 'billing'
        ];
    }

    // 5. Today's check-ins
    $todayCheckins = (int)$pdo->query("
        SELECT COUNT(*)
        FROM public_bookings
        WHERE DATE(check_in_date) = CURDATE()
    ")->fetchColumn();

    if ($todayCheckins > 0) {
        $notifications[] = [
            'icon' => 'fa-arrow-right-to-bracket',
            'title' => "Today's Check-ins",
            'message' => "{$todayCheckins} guest(s) are scheduled to check in today.",
            'type' => 'frontdesk'
        ];
    }

    // 6. Today's check-outs
    $todayCheckouts = (int)$pdo->query("
        SELECT COUNT(*)
        FROM public_bookings
        WHERE DATE(check_out_date) = CURDATE()
    ")->fetchColumn();

    if ($todayCheckouts > 0) {
        $notifications[] = [
            'icon' => 'fa-arrow-right-from-bracket',
            'title' => "Today's Check-outs",
            'message' => "{$todayCheckouts} guest(s) are scheduled to check out today.",
            'type' => 'frontdesk'
        ];
    }

    // Show newest / most important first, limit final list
    $notifications = array_slice($notifications, 0, 8);

    jsonResponse(true, 'Dashboard notifications loaded successfully.', $notifications);
} catch (Exception $e) {
    jsonResponse(false, 'Failed to load dashboard notifications: ' . $e->getMessage());
}
?>