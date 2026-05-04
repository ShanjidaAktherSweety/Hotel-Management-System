<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/response.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(false, 'Invalid request method.');
}

$id = (int)($_POST['activity_item_id'] ?? 0);
$activity_name = trim($_POST['activity_name'] ?? '');
$activity_type = trim($_POST['activity_type'] ?? '');
$price = (float)($_POST['price'] ?? 0);
$description = trim($_POST['description'] ?? '');
$time_slots = trim($_POST['time_slots'] ?? '');
$age_range = trim($_POST['age_range'] ?? '');
$weight_limit = trim($_POST['weight_limit'] ?? '');
$activity_status = trim($_POST['activity_status'] ?? 'Available');

if ($id <= 0 || $activity_name === '' || $activity_type === '' || $price <= 0 || $description === '') {
    jsonResponse(false, 'Please fill all required activity fields.');
}

$image_path = null;

if (!empty($_FILES['activity_image']['name'])) {
    $uploadDir = __DIR__ . '/../../uploads/activities/';

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $fileName = time() . '_' . basename($_FILES['activity_image']['name']);
    $targetPath = $uploadDir . $fileName;

    if (move_uploaded_file($_FILES['activity_image']['tmp_name'], $targetPath)) {
        $image_path = 'uploads/activities/' . $fileName;
    }
}

try {
    if ($image_path) {
        $stmt = $pdo->prepare("
            UPDATE activity_items
            SET activity_name = :activity_name,
                activity_type = :activity_type,
                price = :price,
                description = :description,
                time_slots = :time_slots,
                age_range = :age_range,
                weight_limit = :weight_limit,
                image_path = :image_path,
                activity_status = :activity_status
            WHERE id = :id
        ");

        $stmt->execute([
            ':activity_name' => $activity_name,
            ':activity_type' => $activity_type,
            ':price' => $price,
            ':description' => $description,
            ':time_slots' => $time_slots !== '' ? $time_slots : null,
            ':age_range' => $age_range !== '' ? $age_range : null,
            ':weight_limit' => $weight_limit !== '' ? $weight_limit : null,
            ':image_path' => $image_path,
            ':activity_status' => $activity_status,
            ':id' => $id
        ]);
    } else {
        $stmt = $pdo->prepare("
            UPDATE activity_items
            SET activity_name = :activity_name,
                activity_type = :activity_type,
                price = :price,
                description = :description,
                time_slots = :time_slots,
                age_range = :age_range,
                weight_limit = :weight_limit,
                activity_status = :activity_status
            WHERE id = :id
        ");

        $stmt->execute([
            ':activity_name' => $activity_name,
            ':activity_type' => $activity_type,
            ':price' => $price,
            ':description' => $description,
            ':time_slots' => $time_slots !== '' ? $time_slots : null,
            ':age_range' => $age_range !== '' ? $age_range : null,
            ':weight_limit' => $weight_limit !== '' ? $weight_limit : null,
            ':activity_status' => $activity_status,
            ':id' => $id
        ]);
    }

    jsonResponse(true, 'Activity item updated successfully.');
} catch (Exception $e) {
    jsonResponse(false, 'Failed to update activity item: ' . $e->getMessage());
}
?>