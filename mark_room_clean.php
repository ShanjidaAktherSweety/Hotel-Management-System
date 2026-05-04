<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/response.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(false, 'Invalid request method.');
}

$room_id = trim($_POST['room_id'] ?? '');

if ($room_id === '') {
    jsonResponse(false, 'Room ID is required.');
}

try {
    $pdo->beginTransaction();

    // 1. Get room details first
    $roomStmt = $pdo->prepare("
        SELECT id, room_number
        FROM rooms
        WHERE id = :id
        LIMIT 1
    ");
    $roomStmt->execute([':id' => $room_id]);
    $room = $roomStmt->fetch(PDO::FETCH_ASSOC);

    if (!$room) {
        $pdo->rollBack();
        jsonResponse(false, 'Room not found.');
    }

    // 2. Update room status
    $updateRoomStmt = $pdo->prepare("
        UPDATE rooms
        SET cleaning_status = 'Clean',
            availability_status = 'Available'
        WHERE id = :id
    ");
    $updateRoomStmt->execute([':id' => $room_id]);

    // 3. Update latest housekeeping task for this room
    $updateTaskStmt = $pdo->prepare("
        UPDATE housekeeping_tasks
        SET cleaning_status = 'Clean',
            room_ready_for_guest = 1,
            supervisor_reviewed = 1
        WHERE room_number = :room_number
          AND cleaning_status IN ('Dirty', 'In Progress')
        ORDER BY id DESC
        LIMIT 1
    ");
    $updateTaskStmt->execute([
        ':room_number' => $room['room_number']
    ]);

    $pdo->commit();

    jsonResponse(true, 'Room marked as clean and available.');
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    jsonResponse(false, 'Failed to update room: ' . $e->getMessage());
}
?>