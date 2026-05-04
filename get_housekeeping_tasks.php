<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/response.php';

try {
    $sql = "SELECT room_number, assigned_staff, cleaning_status, laundry_items, maintenance_needed
            FROM housekeeping_tasks
            ORDER BY id DESC
            LIMIT 20";

    $stmt = $pdo->query($sql);
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

    jsonResponse(true, 'Housekeeping tasks fetched successfully.', $tasks);
} catch (Exception $e) {
    jsonResponse(false, 'Failed to fetch housekeeping tasks.');
}
?>