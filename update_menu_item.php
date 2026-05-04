<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/response.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(false, 'Invalid request method.');
}

$id = (int)($_POST['menu_item_id'] ?? 0);
$item_name = trim($_POST['item_name'] ?? '');
$category = trim($_POST['category'] ?? '');
$price = (float)($_POST['price'] ?? 0);
$description = trim($_POST['description'] ?? '');
$item_status = trim($_POST['item_status'] ?? 'Available');

if ($id <= 0 || $item_name === '' || $category === '' || $price <= 0 || $description === '') {
    jsonResponse(false, 'Please fill all required menu fields.');
}

$image_path = null;

if (!empty($_FILES['menu_image']['name'])) {
    $uploadDir = __DIR__ . '/../../uploads/menu/';

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $fileName = time() . '_' . basename($_FILES['menu_image']['name']);
    $targetPath = $uploadDir . $fileName;

    if (move_uploaded_file($_FILES['menu_image']['tmp_name'], $targetPath)) {
        $image_path = 'uploads/menu/' . $fileName;
    }
}

try {
    if ($image_path) {
        $stmt = $pdo->prepare("
            UPDATE restaurant_menu_items
            SET item_name = :item_name,
                category = :category,
                price = :price,
                description = :description,
                image_path = :image_path,
                item_status = :item_status
            WHERE id = :id
        ");

        $stmt->execute([
            ':item_name' => $item_name,
            ':category' => $category,
            ':price' => $price,
            ':description' => $description,
            ':image_path' => $image_path,
            ':item_status' => $item_status,
            ':id' => $id
        ]);
    } else {
        $stmt = $pdo->prepare("
            UPDATE restaurant_menu_items
            SET item_name = :item_name,
                category = :category,
                price = :price,
                description = :description,
                item_status = :item_status
            WHERE id = :id
        ");

        $stmt->execute([
            ':item_name' => $item_name,
            ':category' => $category,
            ':price' => $price,
            ':description' => $description,
            ':item_status' => $item_status,
            ':id' => $id
        ]);
    }

    jsonResponse(true, 'Menu item updated successfully.');
} catch (Exception $e) {
    jsonResponse(false, 'Failed to update menu item: ' . $e->getMessage());
}
?>