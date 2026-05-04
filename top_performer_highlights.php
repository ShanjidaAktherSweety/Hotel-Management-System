<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/response.php';

try {
    $stmt = $pdo->query("
        SELECT staff_name, department, overall_rating, review_notes
        FROM staff_performance
        ORDER BY
            CASE
                WHEN overall_rating LIKE '5%' THEN 5
                WHEN overall_rating LIKE '4%' THEN 4
                WHEN overall_rating LIKE '3%' THEN 3
                WHEN overall_rating LIKE '2%' THEN 2
                WHEN overall_rating LIKE '1%' THEN 1
                ELSE 0
            END DESC,
            id DESC
        LIMIT 3
    ");

    $topPerformers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    jsonResponse(true, 'Top performer highlights loaded successfully.', $topPerformers);
} catch (Exception $e) {
    jsonResponse(false, 'Failed to load top performer highlights: ' . $e->getMessage());
}
?>