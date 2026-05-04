<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/response.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(false, 'Invalid request method.');
}

$item_name = trim($_POST['item_name'] ?? '');
$category = trim($_POST['category'] ?? '');
$price = (float)($_POST['price'] ?? 0);
$description = trim($_POST['description'] ?? '');
$item_status = trim($_POST['item_status'] ?? 'Available');

if ($item_name === '' || $category === '' || $price <= 0 || $description === '') {
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
    $stmt = $pdo->prepare("
        INSERT INTO restaurant_menu_items
        (item_name, category, price, description, image_path, item_status)
        VALUES
        (:item_name, :category, :price, :description, :image_path, :item_status)
    ");

    $stmt->execute([
        ':item_name' => $item_name,
        ':category' => $category,
        ':price' => $price,
        ':description' => $description,
        ':image_path' => $image_path,
        ':item_status' => $item_status
    ]);

    jsonResponse(true, 'Menu item added successfully.');
} catch (Exception $e) {
    jsonResponse(false, 'Failed to add menu item: ' . $e->getMessage());
}
?>