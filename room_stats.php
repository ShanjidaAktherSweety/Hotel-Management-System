<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/response.php';

try {
    $totalRooms = $pdo->query("SELECT COUNT(*) FROM rooms")->fetchColumn();
    $availableRooms = $pdo->query("SELECT COUNT(*) FROM rooms WHERE availability_status = 'Available'")->fetchColumn();
    $cleaningPending = $pdo->query("SELECT COUNT(*) FROM rooms WHERE cleaning_status IN ('Dirty', 'Under Cleaning')")->fetchColumn();
    $maintenanceRooms = $pdo->query("SELECT COUNT(*) FROM rooms WHERE maintenance_status IN ('Repair Needed', 'Under Maintenance')")->fetchColumn();

    jsonResponse(true, 'Room stats loaded successfully.', [
        'total_rooms' => $totalRooms,
        'available_rooms' => $availableRooms,
        'cleaning_pending' => $cleaningPending,
        'maintenance_rooms' => $maintenanceRooms
    ]);
} catch (Exception $e) {
    jsonResponse(false, 'Failed to load room stats.');
}
?>