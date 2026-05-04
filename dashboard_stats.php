<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/response.php';

try {
    $totalBookings = $pdo->query("SELECT COUNT(*) FROM public_bookings")->fetchColumn();

    $todayCheckins = $pdo->query("SELECT COUNT(*) FROM public_bookings WHERE check_in_date = CURDATE()")->fetchColumn();

    $todayCheckouts = $pdo->query("SELECT COUNT(*) FROM public_bookings WHERE check_out_date = CURDATE()")->fetchColumn();

    $activityBookings = $pdo->query("SELECT COUNT(*) FROM activity_bookings")->fetchColumn();

    jsonResponse(true, "Dashboard stats loaded successfully", [
        "total_bookings" => $totalBookings,
        "today_checkins" => $todayCheckins,
        "today_checkouts" => $todayCheckouts,
        "activity_bookings" => $activityBookings
    ]);
} catch (Exception $e) {
    jsonResponse(false, "Failed to load dashboard stats");
}
?>