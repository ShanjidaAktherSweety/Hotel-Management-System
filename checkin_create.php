<?php
session_start();

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/response.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(false, 'Invalid request method.');
}

$booking_id = trim($_POST['booking_id'] ?? '');
$guest_name = trim($_POST['guest_name'] ?? '');
$guest_verification_id = trim($_POST['guest_verification_id'] ?? '');
$assigned_room_id = trim($_POST['assigned_room_id'] ?? '');
$activity_pass = trim($_POST['activity_pass'] ?? '');
$deposit_collected = trim($_POST['deposit_collected'] ?? '0');

if ($booking_id === '' || $guest_name === '' || $guest_verification_id === '' || $assigned_room_id === '') {
    jsonResponse(false, 'Please fill all required check-in fields.');
}

try {
    $pdo->beginTransaction();

    $bookingStmt = $pdo->prepare("
        SELECT id, full_name, room_type, assigned_room_id, assigned_room_number, booking_status, checkin_status, checkout_status
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

    if (strcasecmp(trim($booking['full_name']), $guest_name) !== 0) {
        $pdo->rollBack();
        jsonResponse(false, 'Guest name does not match the booking record.');
    }

    if ($booking['booking_status'] === 'Cancelled') {
        $pdo->rollBack();
        jsonResponse(false, 'Cancelled booking cannot be checked in.');
    }

    if ((int)$booking['checkout_status'] === 1 || $booking['booking_status'] === 'Checked Out') {
        $pdo->rollBack();
        jsonResponse(false, 'This booking is already checked out.');
    }

    if ((int)$booking['checkin_status'] === 1 || $booking['booking_status'] === 'Checked In') {
        $pdo->rollBack();
        jsonResponse(false, 'This booking is already checked in.');
    }

    $roomStmt = $pdo->prepare("
        SELECT id, room_number, room_type, availability_status, cleaning_status, maintenance_status
        FROM rooms
        WHERE id = :room_id
        LIMIT 1
    ");
    $roomStmt->execute([':room_id' => $assigned_room_id]);
    $room = $roomStmt->fetch(PDO::FETCH_ASSOC);

    if (!$room) {
        $pdo->rollBack();
        jsonResponse(false, 'Selected room not found.');
    }

    if ($room['room_type'] !== $booking['room_type']) {
        $pdo->rollBack();
        jsonResponse(false, 'Selected room does not match the booked room type.');
    }

    if (!empty($booking['assigned_room_id']) && (int)$booking['assigned_room_id'] !== (int)$room['id']) {
        $pdo->rollBack();
        jsonResponse(false, 'This booking already has a different assigned room. Please use the assigned room only.');
    }

    if ($room['availability_status'] === 'Occupied') {
        $pdo->rollBack();
        jsonResponse(false, 'Selected room is already occupied.');
    }

    if ($room['availability_status'] === 'Reserved' && (int)($booking['assigned_room_id'] ?? 0) !== (int)$room['id']) {
        $pdo->rollBack();
        jsonResponse(false, 'Selected room is reserved for another booking.');
    }

    if (!in_array($room['availability_status'], ['Available', 'Reserved'], true)) {
        $pdo->rollBack();
        jsonResponse(false, 'Selected room is not ready for check-in.');
    }

    if (!empty($room['cleaning_status']) && $room['cleaning_status'] !== 'Clean') {
        $pdo->rollBack();
        jsonResponse(false, 'Selected room is not clean and ready.');
    }

    if (!empty($room['maintenance_status']) && $room['maintenance_status'] !== 'No Issue') {
        $pdo->rollBack();
        jsonResponse(false, 'Selected room has a maintenance issue.');
    }

    $assigned_room_number = $room['room_number'];
    $assigned_by = $_SESSION['username'] ?? 'staff';

    $updateBooking = $pdo->prepare("
        UPDATE public_bookings
        SET assigned_room_id = :assigned_room_id,
            assigned_room_number = :assigned_room_number,
            assigned_by = :assigned_by,
            guest_verification_id = :guest_verification_id,
            activity_pass = :activity_pass,
            deposit_collected = :deposit_collected,
            booking_status = 'Checked In',
            checkin_status = 1,
            checked_in_at = NOW()
        WHERE id = :booking_id
    ");
    $updateBooking->execute([
        ':assigned_room_id' => $room['id'],
        ':assigned_room_number' => $assigned_room_number,
        ':assigned_by' => $assigned_by,
        ':guest_verification_id' => $guest_verification_id,
        ':activity_pass' => $activity_pass !== '' ? $activity_pass : null,
        ':deposit_collected' => $deposit_collected !== '' ? $deposit_collected : 0,
        ':booking_id' => $booking['id']
    ]);

    $updateRoom = $pdo->prepare("
        UPDATE rooms
        SET availability_status = 'Occupied'
        WHERE id = :room_id
    ");
    $updateRoom->execute([':room_id' => $room['id']]);

    $logStmt = $pdo->prepare("
        INSERT INTO frontdesk_logs (booking_id, guest_name, room_number, action_type, action_status)
        VALUES (:booking_id, :guest_name, :room_number, 'Check-In', 'Completed')
    ");
    $logStmt->execute([
        ':booking_id' => $booking['id'],
        ':guest_name' => $guest_name,
        ':room_number' => $assigned_room_number
    ]);

        if ($activity_pass !== '') {
        $checkActivityStmt = $pdo->prepare("
            SELECT id
            FROM activity_bookings
            WHERE guest_name = :guest_name
              AND booking_date = CURDATE()
              AND activity_type = :activity_type
            LIMIT 1
        ");
        $checkActivityStmt->execute([
            ':guest_name' => $guest_name,
            ':activity_type' => $activity_pass
        ]);

        $existingActivity = $checkActivityStmt->fetch(PDO::FETCH_ASSOC);

        if (!$existingActivity) {
            $activityInsertStmt = $pdo->prepare("
                INSERT INTO activity_bookings (
                    guest_name,
                    activity_type,
                    booking_date,
                    time_slot,
                    guest_weight,
                    guest_age,
                    assigned_staff,
                    billing_type,
                    notes,
                    safety_acknowledged,
                    equipment_issued,
                    external_customer,
                    room_bill_added,
                    status
                ) VALUES (
                    :guest_name,
                    :activity_type,
                    CURDATE(),
                    :time_slot,
                    NULL,
                    NULL,
                    :assigned_staff,
                    :billing_type,
                    :notes,
                    1,
                    0,
                    0,
                    1,
                    'Confirmed'
                )
            ");

            $activityInsertStmt->execute([
                ':guest_name' => $guest_name,
                ':activity_type' => $activity_pass,
                ':time_slot' => 'Check-In Activity',
                ':assigned_staff' => 'Front Desk',
                ':billing_type' => 'Add to Room Bill',
                ':notes' => 'Automatically added during guest check-in.'
            ]);
        }
    }

    $pdo->commit();

    jsonResponse(true, 'Check-in completed successfully.');
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    jsonResponse(false, 'Failed to complete check-in: ' . $e->getMessage());
}
?>