<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/response.php';

try {
    $stmt = $pdo->query("
        SELECT shift_type, COUNT(*) AS total_shifts
        FROM staff_shifts
        GROUP BY shift_type
        ORDER BY shift_type ASC
    ");

    $distribution = $stmt->fetchAll(PDO::FETCH_ASSOC);

    jsonResponse(true, 'Shift distribution loaded successfully.', $distribution);
} catch (Exception $e) {
    jsonResponse(false, 'Failed to load shift distribution: ' . $e->getMessage());
}
?>