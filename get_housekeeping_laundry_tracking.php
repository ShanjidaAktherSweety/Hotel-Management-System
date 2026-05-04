<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/response.php';

try {
    $stmt = $pdo->query("
        SELECT
            COALESCE(SUM(laundry_items), 0) AS total_laundry_items,
            SUM(CASE WHEN laundry_items > 0 AND laundry_collected = 0 THEN 1 ELSE 0 END) AS pending_laundry_tasks,
            SUM(CASE WHEN laundry_collected = 1 THEN 1 ELSE 0 END) AS collected_laundry_tasks
        FROM housekeeping_tasks
    ");

    $summary = $stmt->fetch(PDO::FETCH_ASSOC);

    $data = [
        [
            'label' => 'Total Laundry Items',
            'value' => (int)$summary['total_laundry_items']
        ],
        [
            'label' => 'Pending Laundry Tasks',
            'value' => (int)$summary['pending_laundry_tasks']
        ],
        [
            'label' => 'Collected Laundry Tasks',
            'value' => (int)$summary['collected_laundry_tasks']
        ]
    ];

    jsonResponse(true, 'Laundry tracking loaded successfully.', $data);
} catch (Exception $e) {
    jsonResponse(false, 'Failed to load laundry tracking: ' . $e->getMessage());
}
?>