<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/response.php';

try {
    $stmt = $pdo->query("
        SELECT staff_name, department, shift_type, start_time, end_time, shift_status, shift_date
        FROM staff_shifts
        ORDER BY shift_date DESC, start_time ASC
    ");
    $shifts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    jsonResponse(true, 'Shift records fetched successfully.', $shifts);
} catch (Exception $e) {
    jsonResponse(false, 'Failed to fetch shift records: ' . $e->getMessage());
}
?>