<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/response.php';

try {
    $stmt = $pdo->query("
        SELECT full_name, role, department
        FROM staff_records
        WHERE employment_status = 'Active'
        ORDER BY full_name ASC
    ");
    $staff = $stmt->fetchAll(PDO::FETCH_ASSOC);

    jsonResponse(true, 'Staff dropdown loaded successfully.', $staff);
} catch (Exception $e) {
    jsonResponse(false, 'Failed to load staff dropdown: ' . $e->getMessage());
}
?>