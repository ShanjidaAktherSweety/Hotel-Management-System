<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/response.php';

try {
    $totalStaff = (int)$pdo->query("SELECT COUNT(*) FROM staff_records")->fetchColumn();
    $activeStaff = (int)$pdo->query("SELECT COUNT(*) FROM staff_records WHERE employment_status = 'Active'")->fetchColumn();
    $departments = (int)$pdo->query("SELECT COUNT(DISTINCT department) FROM staff_records")->fetchColumn();
    $newThisMonth = (int)$pdo->query("SELECT COUNT(*) FROM staff_records WHERE MONTH(joining_date) = MONTH(CURDATE()) AND YEAR(joining_date) = YEAR(CURDATE())")->fetchColumn();

    jsonResponse(true, 'Staff stats loaded successfully.', [
        'total_staff' => $totalStaff,
        'active_staff' => $activeStaff,
        'departments' => $departments,
        'new_this_month' => $newThisMonth
    ]);
} catch (Exception $e) {
    jsonResponse(false, 'Failed to load staff stats: ' . $e->getMessage());
}
?>