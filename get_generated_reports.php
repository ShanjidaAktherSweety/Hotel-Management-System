<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/response.php';

try {
    $stmt = $pdo->query("
        SELECT report_id,
               department,
               period_label,
               report_status,
               report_format
        FROM generated_reports
        ORDER BY id DESC
        LIMIT 20
    ");

    $reports = $stmt->fetchAll(PDO::FETCH_ASSOC);

    jsonResponse(true, 'Generated reports fetched successfully.', $reports);
} catch (Exception $e) {
    jsonResponse(false, 'Failed to fetch generated reports: ' . $e->getMessage());
}
?>