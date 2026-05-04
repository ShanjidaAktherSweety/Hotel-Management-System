<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/response.php';

try {
    $stmt = $pdo->query("
        SELECT
            hk.assigned_staff,
            hk.maintenance_needed,
            hk.housekeeping_notes,
            r.room_number
        FROM housekeeping_tasks hk
        LEFT JOIN rooms r ON hk.room_id = r.id
        WHERE hk.maintenance_needed IS NOT NULL
          AND hk.maintenance_needed != ''
          AND hk.maintenance_needed != 'No'
        ORDER BY hk.id DESC
        LIMIT 5
    ");

    $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

    jsonResponse(true, 'Maintenance requests loaded successfully.', $requests);
} catch (Exception $e) {
    jsonResponse(false, 'Failed to load maintenance requests: ' . $e->getMessage());
}
?>