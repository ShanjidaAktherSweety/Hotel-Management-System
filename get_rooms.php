<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/response.php';

try {
    $sql = "SELECT * FROM rooms ORDER BY room_number ASC";
    $stmt = $pdo->query($sql);
    $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

    jsonResponse(true, 'Rooms fetched successfully.', $rooms);
} catch (Exception $e) {
    jsonResponse(false, 'Failed to fetch rooms.');
}
?>