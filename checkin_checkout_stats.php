<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/response.php';

try {
    $todayCheckins = $pdo->query("SELECT COUNT(*) FROM public_bookings WHERE DATE(checked_in_at) = CURDATE()")->fetchColumn();
    $todayCheckouts = $pdo->query("SELECT COUNT(*) FROM public_bookings WHERE DATE(checked_out_at) = CURDATE()")->fetchColumn();
    $depositsCollected = $pdo->query("SELECT COALESCE(SUM(deposit_collected), 0) FROM public_bookings WHERE DATE(checked_in_at) = CURDATE()")->fetchColumn();
    $completedSettlements = $pdo->query("SELECT COUNT(*) FROM public_bookings WHERE checkout_status = 1")->fetchColumn();

    jsonResponse(true, 'Check-in/check-out stats loaded successfully.', [
        'today_checkins' => $todayCheckins,
        'today_checkouts' => $todayCheckouts,
        'deposits_collected' => $depositsCollected,
        'completed_settlements' => $completedSettlements
    ]);
} catch (Exception $e) {
    jsonResponse(false, 'Failed to load stats.');
}
?>