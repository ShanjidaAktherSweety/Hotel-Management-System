<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/response.php';

try {
    $stmt = $pdo->query("
        SELECT
            assigned_staff,
            COUNT(*) AS total_bookings,
            GROUP_CONCAT(DISTINCT activity_type SEPARATOR ', ') AS activities
        FROM activity_bookings
        WHERE assigned_staff IS NOT NULL
          AND assigned_staff != ''
        GROUP BY assigned_staff
        ORDER BY total_bookings DESC, assigned_staff ASC
        LIMIT 5
    ");

    $assignments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    jsonResponse(true, 'Activity staff assignments loaded successfully.', $assignments);
} catch (Exception $e) {
    jsonResponse(false, 'Failed to load activity staff assignments: ' . $e->getMessage());
}
?>