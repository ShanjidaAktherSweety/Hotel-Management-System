<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/response.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(false, 'Invalid request method.');
}

$room_id = (int)($_POST['room_id'] ?? 0);

if ($room_id <= 0) {
    jsonResponse(false, 'Invalid room ID.');
}

try {
    $stmt = $pdo->prepare("DELETE FROM rooms WHERE id = :room_id");
    $stmt->execute([':room_id' => $room_id]);

    jsonResponse(true, 'Room deleted successfully.');
} catch (Exception $e) {
    jsonResponse(false, 'Failed to delete room: ' . $e->getMessage());
}
?>