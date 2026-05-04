<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/response.php';

$bookingId = $_GET['booking_id'] ?? '';

if (empty($bookingId)) {
    jsonResponse(false, 'Booking ID is required.');
}

try {
    // ✅ ACTIVITY CHARGE
    $activityStmt = $pdo->prepare("
        SELECT COALESCE(SUM(ai.price), 0) AS activity_charge
        FROM activity_bookings ab
        LEFT JOIN activity_items ai
            ON LOWER(TRIM(ab.activity_type)) = LOWER(TRIM(ai.activity_name))
        WHERE ab.booking_id = ?
        AND ab.status != 'Cancelled'
    ");
    $activityStmt->execute([$bookingId]);
    $activityCharge = $activityStmt->fetchColumn();

    // ✅ RESTAURANT CHARGE
    $restaurantStmt = $pdo->prepare("
        SELECT COALESCE(SUM(item_charge * quantity), 0) AS service_charge
        FROM restaurant_orders
        WHERE booking_id = ?
        AND (
            add_to_room_bill = 1
            OR billing_option = 'Add to Guest Room Bill'
        )
    ");
    $restaurantStmt->execute([$bookingId]);
    $serviceCharge = $restaurantStmt->fetchColumn();

    jsonResponse(true, 'Extra charges loaded successfully.', [
        'activity_charge' => $activityCharge,
        'service_charge' => $serviceCharge,
        'booking_id' => $bookingId
    ]);

} catch (Exception $e) {
    jsonResponse(false, 'Error: ' . $e->getMessage());
}