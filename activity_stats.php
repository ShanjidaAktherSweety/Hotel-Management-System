<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/response.php';

try {
    $total = $pdo->query("SELECT COUNT(*) FROM activity_bookings")->fetchColumn();
    $zipline = $pdo->query("SELECT COUNT(*) FROM activity_bookings WHERE activity_type = 'Zipline'")->fetchColumn();
    $swimming = $pdo->query("SELECT COUNT(*) FROM activity_bookings WHERE activity_type = 'Swimming Pool'")->fetchColumn();

    jsonResponse(true, 'Activity stats loaded successfully.', [
        'total_activity_bookings' => $total,
        'zipline_sessions' => $zipline,
        'swimming_sessions' => $swimming
    ]);
} catch (Exception $e) {
    jsonResponse(false, 'Failed to load activity stats.');
}
?>