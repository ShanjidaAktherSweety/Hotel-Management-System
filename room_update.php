<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/response.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(false, 'Invalid request method.');
}

$room_id = (int)($_POST['room_id'] ?? 0);
$room_number = trim($_POST['room_number'] ?? '');
$room_type = trim($_POST['room_type'] ?? '');
$price_per_night = (float)($_POST['price_per_night'] ?? 0);
$capacity = (int)($_POST['capacity'] ?? 0);
$availability_status = trim($_POST['availability_status'] ?? '');
$cleaning_status = trim($_POST['cleaning_status'] ?? '');
$maintenance_status = trim($_POST['maintenance_status'] ?? '');
$floor_number = trim($_POST['floor_number'] ?? '');
$description = trim($_POST['description'] ?? '');

if ($room_id <= 0 || $room_number === '' || $room_type === '' || $price_per_night <= 0 || $capacity <= 0) {
    jsonResponse(false, 'Please fill all required room fields.');
}

$oldImageStmt = $pdo->prepare("SELECT image_path FROM rooms WHERE id = :room_id LIMIT 1");
$oldImageStmt->execute([':room_id' => $room_id]);
$oldRoom = $oldImageStmt->fetch(PDO::FETCH_ASSOC);

if (!$oldRoom) {
    jsonResponse(false, 'Room not found.');
}

$image_path = $oldRoom['image_path'];

try {
    if (!empty($_FILES['room_image']['name']) && $_FILES['room_image']['error'] === 0) {
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


    $stmt = $pdo->prepare("
        UPDATE rooms
        SET room_number = :room_number,
            room_type = :room_type,
            price_per_night = :price_per_night,
            capacity = :capacity,
            availability_status = :availability_status,
            cleaning_status = :cleaning_status,
            maintenance_status = :maintenance_status,
            floor_number = :floor_number,
            description = :description,
            image_path = :image_path
        WHERE id = :room_id
    ");

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
        ':image_path' => $image_path,
        ':room_id' => $room_id
    ]);

    jsonResponse(true, 'Room updated successfully.');
} catch (Exception $e) {
    jsonResponse(false, 'Failed to update room: ' . $e->getMessage());
}
?>