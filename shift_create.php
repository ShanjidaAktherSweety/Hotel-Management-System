<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/response.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(false, 'Invalid request method.');
}

$staff_name = trim($_POST['staff_name'] ?? '');
$department = trim($_POST['department'] ?? '');
$role = trim($_POST['role'] ?? '');
$shift_date = trim($_POST['shift_date'] ?? '');
$shift_type = trim($_POST['shift_type'] ?? '');
$shift_status = trim($_POST['shift_status'] ?? '');
$start_time = trim($_POST['start_time'] ?? '');
$end_time = trim($_POST['end_time'] ?? '');
$shift_notes = trim($_POST['shift_notes'] ?? '');

$staff_notified = isset($_POST['staff_notified']) ? 1 : 0;
$supervisor_approved = isset($_POST['supervisor_approved']) ? 1 : 0;
$backup_staff_assigned = isset($_POST['backup_staff_assigned']) ? 1 : 0;
$overtime_allowed = isset($_POST['overtime_allowed']) ? 1 : 0;

if (
    $staff_name === '' ||
    $department === '' ||
    $role === '' ||
    $shift_date === '' ||
    $shift_type === '' ||
    $shift_status === '' ||
    $start_time === '' ||
    $end_time === ''
) {
    jsonResponse(false, 'Please fill all required shift fields.');
}
if ($start_time >= $end_time) {
    jsonResponse(false, 'Shift end time must be after start time.');
}

$conflictStmt = $pdo->prepare("
    SELECT id
    FROM staff_shifts
    WHERE staff_name = :staff_name
      AND shift_date = :shift_date
      AND (
            :start_time < end_time
            AND :end_time > start_time
          )
    LIMIT 1
");

$conflictStmt->execute([
    ':staff_name' => $staff_name,
    ':shift_date' => $shift_date,
    ':start_time' => $start_time,
    ':end_time' => $end_time
]);

if ($conflictStmt->fetch()) {
    jsonResponse(false, 'This staff member already has an overlapping shift on the selected date.');
}

try {
    $stmt = $pdo->prepare("
        INSERT INTO staff_shifts (
            staff_name, department, role, shift_date, shift_type, shift_status,
            start_time, end_time, shift_notes,
            staff_notified, supervisor_approved, backup_staff_assigned, overtime_allowed
        ) VALUES (
            :staff_name, :department, :role, :shift_date, :shift_type, :shift_status,
            :start_time, :end_time, :shift_notes,
            :staff_notified, :supervisor_approved, :backup_staff_assigned, :overtime_allowed
        )
    ");

    $stmt->execute([
        ':staff_name' => $staff_name,
        ':department' => $department,
        ':role' => $role,
        ':shift_date' => $shift_date,
        ':shift_type' => $shift_type,
        ':shift_status' => $shift_status,
        ':start_time' => $start_time,
        ':end_time' => $end_time,
        ':shift_notes' => $shift_notes !== '' ? $shift_notes : null,
        ':staff_notified' => $staff_notified,
        ':supervisor_approved' => $supervisor_approved,
        ':backup_staff_assigned' => $backup_staff_assigned,
        ':overtime_allowed' => $overtime_allowed
    ]);

    jsonResponse(true, 'Shift saved successfully.');
} catch (Exception $e) {
    jsonResponse(false, 'Failed to save shift: ' . $e->getMessage());
}
?>