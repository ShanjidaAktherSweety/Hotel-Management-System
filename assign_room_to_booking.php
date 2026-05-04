<?php
session_start();

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/response.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(false, 'Invalid request method.');
}

$booking_id = trim($_POST['booking_id'] ?? '');
$room_id = trim($_POST['room_id'] ?? '');

if ($booking_id === '' || $room_id === '') {
    jsonResponse(false, 'Booking ID and room are required.');
}

try {
    $pdo->beginTransaction();

    $bookingStmt = $pdo->prepare("
        SELECT id, room_type, assigned_room_id, booking_status, checkin_status, checkout_status
        FROM public_bookings
        WHERE id = :booking_id
        LIMIT 1
    ");
    $bookingStmt->execute([':booking_id' => $booking_id]);
    $booking = $bookingStmt->fetch(PDO::FETCH_ASSOC);

    if (!$booking) {
        $pdo->rollBack();
        jsonResponse(false, 'Booking not found.');
    }

    if ($booking['booking_status'] === 'Cancelled') {
        $pdo->rollBack();
        jsonResponse(false, 'Cancelled booking cannot be assigned a room.');
    }

    if ((int)$booking['checkout_status'] === 1 || $booking['booking_status'] === 'Checked Out') {
        $pdo->rollBack();
        jsonResponse(false, 'Checked-out booking cannot be assigned a room.');
    }

    if ((int)$booking['checkin_status'] === 1 || $booking['booking_status'] === 'Checked In') {
        $pdo->rollBack();
        jsonResponse(false, 'This booking is already checked in.');
    }

    if (!empty($booking['assigned_room_id'])) {
        $pdo->rollBack();
        jsonResponse(false, 'A room is already assigned to this booking.');
    }

    $roomStmt = $pdo->prepare("
        SELECT id, room_number, room_type, availability_status, cleaning_status, maintenance_status
        FROM rooms
        WHERE id = :room_id
        LIMIT 1
    ");
    $roomStmt->execute([':room_id' => $room_id]);
    $room = $roomStmt->fetch(PDO::FETCH_ASSOC);

    if (!$room) {
        $pdo->rollBack();
        jsonResponse(false, 'Selected room not found.');
    }

    if ($room['room_type'] !== $booking['room_type']) {
        $pdo->rollBack();
        jsonResponse(false, 'Selected room does not match the booked room type.');
    }

    if ($room['availability_status'] !== 'Available') {
        $pdo->rollBack();
        jsonResponse(false, 'Selected room is not available.');
    }

    if (!empty($room['cleaning_status']) && $room['cleaning_status'] !== 'Clean') {
        $pdo->rollBack();
        jsonResponse(false, 'Selected room is not clean and ready.');
    }

    if (!empty($room['maintenance_status']) && $room['maintenance_status'] !== 'No Issue') {
        $pdo->rollBack();
        jsonResponse(false, 'Selected room has a maintenance issue.');
    }

    $assignedBy = $_SESSION['username'] ?? 'staff';

    $updateBooking = $pdo->prepare("
        UPDATE public_bookings
        SET assigned_room_id = :room_id,
            assigned_room_number = :room_number,
            assigned_by = :assigned_by,
            booking_status = 'Confirmed'
        WHERE id = :booking_id
    ");
    $updateBooking->execute([
        ':room_id' => $room['id'],
        ':room_number' => $room['room_number'],
        ':assigned_by' => $assignedBy,
        ':booking_id' => $booking['id']
    ]);

    $updateRoom = $pdo->prepare("
        UPDATE rooms
        SET availability_status = 'Reserved'
        WHERE id = :room_id
    ");
    $updateRoom->execute([':room_id' => $room['id']]);

    $pdo->commit();

    jsonResponse(true, 'Room assigned successfully.');
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    jsonResponse(false, 'Failed to assign room: ' . $e->getMessage());
}
?>