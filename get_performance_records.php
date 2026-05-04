<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/response.php';

try {
    $stmt = $pdo->query("
        SELECT staff_name, department, attendance_rate, task_completion, overall_rating, service_quality
        FROM staff_performance
        ORDER BY id DESC
    ");

    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);

    jsonResponse(true, 'Performance records fetched successfully.', $records);
} catch (Exception $e) {
    jsonResponse(false, 'Failed to fetch performance records: ' . $e->getMessage());
}
?>