<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/response.php';

$role = trim($_GET['role'] ?? '');
$department = trim($_GET['department'] ?? '');
$status = trim($_GET['status'] ?? '');

try {
    $sql = "
        SELECT full_name, role, department, employment_status, phone, access_level
        FROM staff_records
        WHERE 1=1
    ";

    $params = [];

    if ($role !== '') {
        $sql .= " AND role = :role";
        $params[':role'] = $role;
    }

    if ($department !== '') {
        $sql .= " AND department = :department";
        $params[':department'] = $department;
    }

    if ($status !== '') {
        $sql .= " AND employment_status = :status";
        $params[':status'] = $status;
    }

    $sql .= " ORDER BY id DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    $staff = $stmt->fetchAll(PDO::FETCH_ASSOC);

    jsonResponse(true, 'Filtered staff records loaded successfully.', $staff);
} catch (Exception $e) {
    jsonResponse(false, 'Failed to filter staff records: ' . $e->getMessage());
}
?>