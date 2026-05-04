<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/response.php';

try {
    $totalBookings = (int)$pdo->query("SELECT COUNT(*) FROM public_bookings")->fetchColumn();
    $todayCheckins = (int)$pdo->query("SELECT COUNT(*) FROM public_bookings WHERE DATE(check_in_date) = CURDATE()")->fetchColumn();
    $todayCheckouts = (int)$pdo->query("SELECT COUNT(*) FROM public_bookings WHERE DATE(check_out_date) = CURDATE()")->fetchColumn();
    $activityBookings = (int)$pdo->query("SELECT COUNT(*) FROM activity_bookings")->fetchColumn();

    $occupiedRooms = (int)$pdo->query("SELECT COUNT(*) FROM rooms WHERE availability_status = 'Occupied'")->fetchColumn();
    $availableRooms = (int)$pdo->query("SELECT COUNT(*) FROM rooms WHERE availability_status = 'Available'")->fetchColumn();
    $cleanRooms = (int)$pdo->query("SELECT COUNT(*) FROM rooms WHERE cleaning_status = 'Clean'")->fetchColumn();
    $dirtyRooms = (int)$pdo->query("SELECT COUNT(*) FROM rooms WHERE cleaning_status = 'Dirty'")->fetchColumn();
    $maintenanceRooms = (int)$pdo->query("SELECT COUNT(*) FROM rooms WHERE maintenance_status = 'Repair Needed'")->fetchColumn();
    $reservedRooms = (int)$pdo->query("SELECT COUNT(*) FROM rooms WHERE availability_status = 'Reserved'")->fetchColumn();

    $totalRooms = (int)$pdo->query("SELECT COUNT(*) FROM rooms")->fetchColumn();
    $occupancyRate = $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100) : 0;

    $revenueToday = (float)$pdo->query("SELECT COALESCE(SUM(total_amount), 0) FROM bills WHERE DATE(created_at) = CURDATE()")->fetchColumn();

    $roomRevenue = (float)$pdo->query("SELECT COALESCE(SUM(room_charge), 0) FROM bills")->fetchColumn();
    $activityRevenue = (float)$pdo->query("SELECT COALESCE(SUM(activity_charge), 0) FROM bills")->fetchColumn();
    $serviceRevenue = (float)$pdo->query("SELECT COALESCE(SUM(service_charge), 0) FROM bills")->fetchColumn();
    $otherRevenue = 0;

    jsonResponse(true, 'Dashboard overview loaded successfully.', [
        'total_bookings' => $totalBookings,
        'today_checkins' => $todayCheckins,
        'today_checkouts' => $todayCheckouts,
        'activity_bookings' => $activityBookings,
        'occupied_rooms' => $occupiedRooms,
        'available_rooms' => $availableRooms,
        'occupancy_rate' => $occupancyRate,
        'revenue_today' => $revenueToday,
        'clean_rooms' => $cleanRooms,
        'dirty_rooms' => $dirtyRooms,
        'maintenance_rooms' => $maintenanceRooms,
        'reserved_rooms' => $reservedRooms,
        'room_revenue' => $roomRevenue,
        'activity_revenue' => $activityRevenue,
        'service_revenue' => $serviceRevenue,
        'other_revenue' => $otherRevenue
    ]);
} catch (Exception $e) {
    jsonResponse(false, 'Failed to load dashboard overview: ' . $e->getMessage());
}
?>