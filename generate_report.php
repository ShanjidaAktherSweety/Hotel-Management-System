<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/response.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(false, 'Invalid request method.');
}

$department = trim($_POST['department'] ?? 'All Departments');
$period_label = trim($_POST['period_label'] ?? 'Current Data');
$report_format = trim($_POST['report_format'] ?? 'Live');

try {
    $report_id = 'REP-' . date('YmdHis');

    $stmt = $pdo->prepare("
        INSERT INTO generated_reports (
            report_id,
            department,
            period_label,
            report_status,
            report_format
        ) VALUES (
            :report_id,
            :department,
            :period_label,
            'Generated',
            :report_format
        )
    ");

    $stmt->execute([
        ':report_id' => $report_id,
        ':department' => $department,
        ':period_label' => $period_label,
        ':report_format' => $report_format
    ]);

    jsonResponse(true, 'Report generated successfully.', [
        'report_id' => $report_id
    ]);
} catch (Exception $e) {
    jsonResponse(false, 'Failed to generate report: ' . $e->getMessage());
}
?>