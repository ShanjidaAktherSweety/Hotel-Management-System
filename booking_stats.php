<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/response.php';

try {
    $total = $pdo->query("SELECT COUNT(*) FROM public_bookings")->fetchColumn();
    $confirmed = $pdo->query("SELECT COUNT(*) FROM public_bookings WHERE booking_status = 'Confirmed'")->fetchColumn();
    $pending = $pdo->query("SELECT COUNT(*) FROM public_bookings WHERE booking_status = 'Pending'")->fetchColumn();
    $groupBookings = $pdo->query("SELECT COUNT(*) FROM public_bookings WHERE booking_type IN ('Corporate Booking', 'Group Booking')")->fetchColumn();

    jsonResponse(true, 'Booking stats loaded successfully.', [
        'total_reservations' => $total,
        'confirmed' => $confirmed,
        'pending' => $pending,
        'group_bookings' => $groupBookings
    ]);
} catch (Exception $e) {
    jsonResponse(false, 'Failed to load booking stats.');
}
?>