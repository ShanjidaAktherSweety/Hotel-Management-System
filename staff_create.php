<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/response.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(false, 'Invalid request method.');
}

$full_name = trim($_POST['full_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$role = trim($_POST['role'] ?? '');
$department = trim($_POST['department'] ?? '');
$employment_status = trim($_POST['employment_status'] ?? '');
$joining_date = trim($_POST['joining_date'] ?? '');
$access_level = trim($_POST['access_level'] ?? '');
$staff_notes = trim($_POST['staff_notes'] ?? '');

$login_account_active = isset($_POST['login_account_active']) ? 1 : 0;
$role_assigned = isset($_POST['role_assigned']) ? 1 : 0;
$department_confirmed = isset($_POST['department_confirmed']) ? 1 : 0;
$notify_employee = isset($_POST['notify_employee']) ? 1 : 0;

if (
    $full_name === '' ||
    $email === '' ||
    $phone === '' ||
    $role === '' ||
    $department === '' ||
    $employment_status === '' ||
    $joining_date === '' ||
    $access_level === ''
) {
    jsonResponse(false, 'Please fill all required staff fields.');
}

try {
    $checkStmt = $pdo->prepare("SELECT id FROM staff_records WHERE email = :email LIMIT 1");
    $checkStmt->execute([':email' => $email]);

    if ($checkStmt->fetch()) {
        jsonResponse(false, 'A staff record with this email already exists.');
    }

    $stmt = $pdo->prepare("
        INSERT INTO staff_records (
            full_name, email, phone, role, department, employment_status,
            joining_date, access_level, staff_notes,
            login_account_active, role_assigned, department_confirmed, notify_employee
        ) VALUES (
            :full_name, :email, :phone, :role, :department, :employment_status,
            :joining_date, :access_level, :staff_notes,
            :login_account_active, :role_assigned, :department_confirmed, :notify_employee
        )
    ");

    $stmt->execute([
        ':full_name' => $full_name,
        ':email' => $email,
        ':phone' => $phone,
        ':role' => $role,
        ':department' => $department,
        ':employment_status' => $employment_status,
        ':joining_date' => $joining_date,
        ':access_level' => $access_level,
        ':staff_notes' => $staff_notes !== '' ? $staff_notes : null,
        ':login_account_active' => $login_account_active,
        ':role_assigned' => $role_assigned,
        ':department_confirmed' => $department_confirmed,
        ':notify_employee' => $notify_employee
    ]);

    jsonResponse(true, 'Staff record saved successfully.');
} catch (Exception $e) {
    jsonResponse(false, 'Failed to save staff record: ' . $e->getMessage());
}
?>