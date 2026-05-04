<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/response.php';

try {
    $stmt = $pdo->query("
        SELECT
            assigned_staff,
            COUNT(*) AS total_tasks,
            SUM(CASE WHEN cleaning_status IN ('Clean', 'Ready for Inspection') THEN 1 ELSE 0 END) AS completed_tasks,
            SUM(CASE WHEN cleaning_status IN ('Dirty', 'Under Cleaning') THEN 1 ELSE 0 END) AS active_tasks
        FROM housekeeping_tasks
        WHERE assigned_staff IS NOT NULL
          AND assigned_staff != ''
        GROUP BY assigned_staff
        ORDER BY total_tasks DESC, assigned_staff ASC
    ");

    $staff = $stmt->fetchAll(PDO::FETCH_ASSOC);

    jsonResponse(true, 'Assigned housekeeping staff loaded successfully.', $staff);
} catch (Exception $e) {
    jsonResponse(false, 'Failed to load assigned staff: ' . $e->getMessage());
}
?>