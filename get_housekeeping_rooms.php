<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/response.php';

try {
    $sql = "SELECT id, room_number, room_type, cleaning_status, availability_status
            FROM rooms
            ORDER BY room_number ASC";
    $stmt = $pdo->query($sql);
    $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

    jsonResponse(true, 'Housekeeping rooms fetched successfully.', $rooms);
} catch (Exception $e) {
    jsonResponse(false, 'Failed to fetch housekeeping rooms: ' . $e->getMessage());
}
?>