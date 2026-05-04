<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/response.php';

$report_id = trim($_GET['report_id'] ?? '');

if ($report_id === '') {
    jsonResponse(false, 'Report ID is required.');
}

try {
    $stmt = $pdo->prepare("
        SELECT report_id,
               department,
               period_label,
               report_status,
               report_format,
               created_at
        FROM generated_reports
        WHERE report_id = :report_id
        LIMIT 1
    ");
    $stmt->execute([':report_id' => $report_id]);

    $report = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$report) {
        jsonResponse(false, 'Report not found.');
    }

    jsonResponse(true, 'Report details loaded successfully.', $report);
} catch (Exception $e) {
    jsonResponse(false, 'Failed to load report details: ' . $e->getMessage());
}
?>