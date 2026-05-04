<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/response.php';

try {
    $stmt = $pdo->query("
        SELECT department, COUNT(*) AS total_staff
        FROM staff_records
        WHERE employment_status = 'Active'
        GROUP BY department
        ORDER BY department ASC
    ");

    $departments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    jsonResponse(true, 'Department distribution loaded successfully.', $departments);
} catch (Exception $e) {
    jsonResponse(false, 'Failed to load department distribution: ' . $e->getMessage());
}
?>