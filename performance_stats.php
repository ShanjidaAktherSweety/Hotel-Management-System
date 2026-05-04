<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/response.php';

try {
    $topPerformers = (int)$pdo->query("
        SELECT COUNT(*)
        FROM staff_performance
        WHERE overall_rating LIKE '5%' OR overall_rating = 'Excellent'
    ")->fetchColumn();

    $taskCompletion = (float)$pdo->query("
        SELECT COALESCE(AVG(task_completion), 0)
        FROM staff_performance
    ")->fetchColumn();

    $punctualityRate = (float)$pdo->query("
        SELECT
            COALESCE(AVG(
                CASE
                    WHEN punctuality = 'Excellent' THEN 100
                    WHEN punctuality = 'Good' THEN 85
                    WHEN punctuality = 'Average' THEN 70
                    WHEN punctuality = 'Poor' THEN 50
                    ELSE 0
                END
            ), 0)
        FROM staff_performance
    ")->fetchColumn();

    $avgRating = (float)$pdo->query("
        SELECT
            COALESCE(AVG(
                CASE
                    WHEN overall_rating LIKE '5%' THEN 5
                    WHEN overall_rating LIKE '4%' THEN 4
                    WHEN overall_rating LIKE '3%' THEN 3
                    WHEN overall_rating LIKE '2%' THEN 2
                    WHEN overall_rating LIKE '1%' THEN 1
                    ELSE 0
                END
            ), 0)
        FROM staff_performance
    ")->fetchColumn();

    jsonResponse(true, 'Performance stats loaded successfully.', [
        'top_performers' => $topPerformers,
        'task_completion' => round($taskCompletion),
        'punctuality_rate' => round($punctualityRate),
        'avg_rating' => round($avgRating, 1)
    ]);
} catch (Exception $e) {
    jsonResponse(false, 'Failed to load performance stats: ' . $e->getMessage());
}
?>