<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/response.php';

try {
    $todayShifts = (int)$pdo->query("SELECT COUNT(*) FROM staff_shifts WHERE shift_date = CURDATE()")->fetchColumn();
    $assignedStaff = (int)$pdo->query("SELECT COUNT(DISTINCT staff_name) FROM staff_shifts WHERE shift_date = CURDATE()")->fetchColumn();
    $upcomingShifts = (int)$pdo->query("SELECT COUNT(*) FROM staff_shifts WHERE shift_date > CURDATE()")->fetchColumn();
    $departmentsCovered = (int)$pdo->query("SELECT COUNT(DISTINCT department) FROM staff_shifts WHERE shift_date = CURDATE()")->fetchColumn();

    jsonResponse(true, 'Shift stats loaded successfully.', [
        'today_shifts' => $todayShifts,
        'assigned_staff' => $assignedStaff,
        'upcoming_shifts' => $upcomingShifts,
        'departments_covered' => $departmentsCovered
    ]);
} catch (Exception $e) {
    jsonResponse(false, 'Failed to load shift stats: ' . $e->getMessage());
}
?>