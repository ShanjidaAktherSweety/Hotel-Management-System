<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/response.php';

try {
    $stmt = $pdo->query("
        SELECT full_name, role, department, employment_status, phone, access_level
        FROM staff_records
        ORDER BY id DESC
    ");
    $staff = $stmt->fetchAll(PDO::FETCH_ASSOC);

    jsonResponse(true, 'Staff records fetched successfully.', $staff);
} catch (Exception $e) {
    jsonResponse(false, 'Failed to fetch staff records: ' . $e->getMessage());
}
?>