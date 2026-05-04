<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/response.php';

try {
    $stmt = $pdo->prepare("
        SELECT full_name, role, department
        FROM staff_records
        WHERE employment_status = 'Active'
          AND department = 'Activities'
        ORDER BY full_name ASC
    ");

    $stmt->execute();
    $staff = $stmt->fetchAll(PDO::FETCH_ASSOC);

    jsonResponse(true, 'Activity staff loaded successfully.', $staff);
} catch (Exception $e) {
    jsonResponse(false, 'Failed to load activity staff: ' . $e->getMessage());
}
?>