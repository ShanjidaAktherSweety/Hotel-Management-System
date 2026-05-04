<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/response.php';

$booking_id = trim($_GET['booking_id'] ?? '');

if ($booking_id === '') {
    jsonResponse(false, 'Booking ID is required.');
}

try {
    $sql = "SELECT id, full_name, assigned_room_number, room_type, check_in_date, check_out_date, deposit_collected
            FROM public_bookings
            WHERE id = :booking_id
            LIMIT 1";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':booking_id' => $booking_id]);
    $booking = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$booking) {
        jsonResponse(false, 'Booking not found.');
    }

    $roomStmt = $pdo->prepare("
        SELECT price_per_night
        FROM rooms
        WHERE room_number = :room_number
        LIMIT 1
    ");
    $roomStmt->execute([
        ':room_number' => $booking['assigned_room_number']
    ]);
    $room = $roomStmt->fetch(PDO::FETCH_ASSOC);

    $pricePerNight = $room ? (float)$room['price_per_night'] : 0;

    $checkIn = new DateTime($booking['check_in_date']);
    $checkOut = new DateTime($booking['check_out_date']);
    $nightsStayed = max(1, (int)$checkIn->diff($checkOut)->days);

    $roomCharge = $pricePerNight * $nightsStayed;

    $invoiceId = 'INV-' . str_pad((string)$booking['id'], 4, '0', STR_PAD_LEFT) . '-' . date('His');

    jsonResponse(true, 'Billing booking details fetched successfully.', [
        'booking_id' => $booking['id'],
        'guest_name' => $booking['full_name'],
        'room_number' => $booking['assigned_room_number'],
        'room_type' => $booking['room_type'],
        'check_in_date' => $booking['check_in_date'],
        'check_out_date' => $booking['check_out_date'],
        'nights_stayed' => $nightsStayed,
        'deposit_amount' => (float)$booking['deposit_collected'],
        'room_charge' => $roomCharge,
        'invoice_id' => $invoiceId,
        'billing_notes' => 'Auto-generated from checked-in booking'
    ]);
} catch (Exception $e) {
    jsonResponse(false, 'Failed to fetch billing details: ' . $e->getMessage());
}
?>