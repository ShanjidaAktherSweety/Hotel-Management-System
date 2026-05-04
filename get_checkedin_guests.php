<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/response.php';

try {
    $sql = "SELECT id, full_name, assigned_room_number, room_type, check_in_date, check_out_date, deposit_collected
            FROM public_bookings
            WHERE checkin_status = 1
              AND checkout_status = 0
            ORDER BY id DESC";

    $stmt = $pdo->query($sql);
    $guests = $stmt->fetchAll(PDO::FETCH_ASSOC);

    jsonResponse(true, 'Checked-in guests fetched successfully.', $guests);
} catch (Exception $e) {
    jsonResponse(false, 'Failed to fetch checked-in guests: ' . $e->getMessage());
}
?>