<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/response.php';

$booking_id = trim($_GET['booking_id'] ?? '');

try {
    if ($booking_id !== '') {
        $bookingStmt = $pdo->prepare("
            SELECT id, room_type, assigned_room_id, assigned_room_number
            FROM public_bookings
            WHERE id = :booking_id
            LIMIT 1
        ");
        $bookingStmt->execute([':booking_id' => $booking_id]);
        $booking = $bookingStmt->fetch(PDO::FETCH_ASSOC);

        if (!$booking) {
            jsonResponse(false, 'Booking not found.');
        }

        // If booking already has an assigned room, return that exact room
        if (!empty($booking['assigned_room_id'])) {
            $roomStmt = $pdo->prepare("
                SELECT id, room_number, room_type
                FROM rooms
                WHERE id = :room_id
                LIMIT 1
            ");
            $roomStmt->execute([':room_id' => $booking['assigned_room_id']]);
            $room = $roomStmt->fetch(PDO::FETCH_ASSOC);

            if ($room) {
                jsonResponse(true, 'Assigned room fetched successfully.', [$room]);
            } else {
                jsonResponse(false, 'Assigned room not found in rooms table.');
            }
        }

        // If no room assigned yet, load available clean rooms by booked room type
        $sql = "SELECT id, room_number, room_type
                FROM rooms
                WHERE availability_status = 'Available'
                  AND cleaning_status = 'Clean'
                  AND room_type = :room_type
                ORDER BY room_number ASC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([':room_type' => $booking['room_type']]);
        $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

        jsonResponse(true, 'Matching rooms fetched successfully.', $rooms);
    } else {
        $sql = "SELECT id, room_number, room_type
                FROM rooms
                WHERE availability_status = 'Available'
                  AND cleaning_status = 'Clean'
                ORDER BY room_number ASC";

        $stmt = $pdo->query($sql);
        $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

        jsonResponse(true, 'Available rooms fetched successfully.', $rooms);
    }
} catch (Exception $e) {
    jsonResponse(false, 'Failed to fetch available rooms: ' . $e->getMessage());
}
?>