<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/response.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(false, 'Invalid request method.');
}

$room_number = trim($_POST['room_number'] ?? '');
$room_type = trim($_POST['room_type'] ?? '');
$price_per_night = trim($_POST['price_per_night'] ?? '');
$capacity = trim($_POST['capacity'] ?? '');
$availability_status = trim($_POST['availability_status'] ?? 'Available');
$cleaning_status = trim($_POST['cleaning_status'] ?? 'Clean');
$maintenance_status = trim($_POST['maintenance_status'] ?? 'No Issue');
$floor_number = trim($_POST['floor_number'] ?? '');
$description = trim($_POST['description'] ?? '');

if (
    $room_number === '' ||
    $room_type === '' ||
    $price_per_night === '' ||
    $capacity === ''
) {
    jsonResponse(false, 'Please fill all required room fields.');
}

try {
    $checkSql = "SELECT id FROM rooms WHERE room_number = :room_number LIMIT 1";
    $checkStmt = $pdo->prepare($checkSql);
    $checkStmt->execute([':room_number' => $room_number]);

    if ($checkStmt->fetch()) {
        jsonResponse(false, 'This room number already exists.');
    }

    $sql = "INSERT INTO rooms (
        room_number, room_type, price_per_night, capacity,
        availability_status, cleaning_status, maintenance_status,
        floor_number, description, image_path
    ) VALUES (
        :room_number, :room_type, :price_per_night, :capacity,
        :availability_status, :cleaning_status, :maintenance_status,
        :floor_number, :description, :image_path
    )";

    $image_path = null;

    if (!empty($_FILES['room_image']['name'])) {
        $uploadDir = __DIR__ . '/../../uploads/rooms/';
        
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = time() . '_' . basename($_FILES['room_image']['name']);
        $targetPath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['room_image']['tmp_name'], $targetPath)) {
            $image_path = 'uploads/rooms/' . $fileName;
        }
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':room_number' => $room_number,
        ':room_type' => $room_type,
        ':price_per_night' => $price_per_night,
        ':capacity' => $capacity,
        ':availability_status' => $availability_status,
        ':cleaning_status' => $cleaning_status,
        ':maintenance_status' => $maintenance_status,
        ':floor_number' => $floor_number !== '' ? $floor_number : null,
        ':description' => $description !== '' ? $description : null,
        ':image_path' => $image_path
    ]);

    jsonResponse(true, 'Room added successfully.');
} catch (Exception $e) {
    jsonResponse(false, 'Failed to save room: ' . $e->getMessage());
}
?>