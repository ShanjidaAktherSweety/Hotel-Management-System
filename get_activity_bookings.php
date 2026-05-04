<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/response.php';

try {
    $sql = "SELECT guest_name, activity_type, time_slot, assigned_staff, status 
            FROM activity_bookings 
            ORDER BY id DESC";
    $stmt = $pdo->query($sql);
    $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

    jsonResponse(true, 'Activity bookings fetched successfully.', $bookings);
} catch (Exception $e) {
    jsonResponse(false, 'Failed to fetch activity bookings.');
}
?>