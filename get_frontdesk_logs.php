<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/response.php';

try {
    $sql = "SELECT guest_name, room_number, action_type, action_status
            FROM frontdesk_logs
            ORDER BY id DESC
            LIMIT 20";
    $stmt = $pdo->query($sql);
    $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    jsonResponse(true, 'Front desk logs fetched successfully.', $logs);
} catch (Exception $e) {
    jsonResponse(false, 'Failed to fetch front desk logs.');
}
?>