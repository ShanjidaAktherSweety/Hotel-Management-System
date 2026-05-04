<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/response.php';

$guest_name = trim($_GET['guest_name'] ?? '');
$room_number = trim($_GET['room_number'] ?? '');

if ($guest_name === '' || $room_number === '') {
    jsonResponse(false, 'Guest name and room number are required.');
}

try {
    // 1. Find the active checked-in booking
    $bookingStmt = $pdo->prepare("
        SELECT id, full_name, assigned_room_number
        FROM public_bookings
        WHERE full_name = :guest_name
          AND assigned_room_number = :room_number
          AND checkin_status = 1
          AND checkout_status = 0
        ORDER BY id DESC
        LIMIT 1
    ");
    $bookingStmt->execute([
        ':guest_name' => $guest_name,
        ':room_number' => $room_number
    ]);
    $booking = $bookingStmt->fetch(PDO::FETCH_ASSOC);

    if (!$booking) {
        jsonResponse(false, 'No active checked-in booking found for this guest and room.');
    }

    // 2. Find the latest bill linked to this booking
    $billStmt = $pdo->prepare("
        SELECT
            id,
            invoice_id,
            guest_name,
            room_number,
            room_charge,
            activity_charge,
            service_charge,
            tax_amount,
            deposit_amount,
            discount_amount,
            refund_amount,
            total_amount,
            invoice_status,
            billing_notes
        FROM bills
        WHERE booking_id = :booking_id
        ORDER BY id DESC
        LIMIT 1
    ");
    $billStmt->execute([
        ':booking_id' => $booking['id']
    ]);
    $bill = $billStmt->fetch(PDO::FETCH_ASSOC);

    if (!$bill) {
        jsonResponse(false, 'No bill found for this checked-in booking.');
    }

    jsonResponse(true, 'Bill details loaded successfully.', $bill);
} catch (Exception $e) {
    jsonResponse(false, 'Failed to load checkout bill: ' . $e->getMessage());
}
?>