<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/response.php';

try {
    $stmt = $pdo->query("
        SELECT id, activity_name, activity_type, price, description,
               time_slots, age_range, weight_limit, image_path, activity_status
        FROM activity_items
        
        ORDER BY id DESC
    ");

    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    jsonResponse(true, 'Activity items fetched successfully.', $items);
} catch (Exception $e) {
    jsonResponse(false, 'Failed to fetch activity items: ' . $e->getMessage());
}
?>