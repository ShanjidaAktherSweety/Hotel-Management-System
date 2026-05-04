<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/response.php';

try {
    $stmt = $pdo->query("
        SELECT
            department,
            ROUND(AVG(
                (
                    task_completion +
                    attendance_rate +
                    CASE
                        WHEN service_quality = 'Excellent' THEN 100
                        WHEN service_quality = 'Good' THEN 85
                        WHEN service_quality = 'Average' THEN 70
                        WHEN service_quality = 'Needs Improvement' THEN 50
                        ELSE 0
                    END +
                    CASE
                        WHEN punctuality = 'Excellent' THEN 100
                        WHEN punctuality = 'Good' THEN 85
                        WHEN punctuality = 'Average' THEN 70
                        WHEN punctuality = 'Poor' THEN 50
                        ELSE 0
                    END
                ) / 4
            )) AS performance_score
        FROM staff_performance
        GROUP BY department
        ORDER BY department ASC
    ");

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    jsonResponse(true, 'Dashboard staff performance loaded successfully.', $rows);
} catch (Exception $e) {
    jsonResponse(false, 'Failed to load dashboard staff performance: ' . $e->getMessage());
}
?>