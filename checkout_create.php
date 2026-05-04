<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/response.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(false, 'Invalid request method.');
}

$guest_name = trim($_POST['guest_name'] ?? '');
$assigned_room_number = trim($_POST['assigned_room_number'] ?? '');
$pending_charges = trim($_POST['pending_charges'] ?? '0');
$final_settlement_method = trim($_POST['final_settlement_method'] ?? '');
$checkout_notes = trim($_POST['checkout_notes'] ?? '');

if ($guest_name === '' || $assigned_room_number === '' || $final_settlement_method === '') {
    jsonResponse(false, 'Please fill all required check-out fields.');
}

try {
    $pdo->beginTransaction();

    $bookingStmt = $pdo->prepare("
        SELECT id, assigned_room_id
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
        ':room_number' => $assigned_room_number
    ]);

    $booking = $bookingStmt->fetch(PDO::FETCH_ASSOC);

    if (!$booking) {
        $pdo->rollBack();
        jsonResponse(false, 'No active checked-in booking found for this guest and room.');
    }

    $updateBooking = $pdo->prepare("
        UPDATE public_bookings
        SET booking_status = 'Checked Out',
            checkout_status = 1,
            checked_out_at = NOW(),
            pending_charges = :pending_charges,
            final_settlement_method = :final_settlement_method,
            checkout_notes = :checkout_notes
        WHERE id = :booking_id
    ");
    $updateBooking->execute([
        ':pending_charges' => $pending_charges !== '' ? $pending_charges : 0,
        ':final_settlement_method' => $final_settlement_method,
        ':checkout_notes' => $checkout_notes !== '' ? $checkout_notes : null,
        ':booking_id' => $booking['id']
    ]);

    if (!empty($booking['assigned_room_id'])) {
        $updateRoom = $pdo->prepare("
            UPDATE rooms
            SET availability_status = 'Cleaning',
                cleaning_status = 'Dirty'
            WHERE id = :id
        ");
        $updateRoom->execute([':id' => $booking['assigned_room_id']]);

        $checkTaskStmt = $pdo->prepare("
            SELECT id
            FROM housekeeping_tasks
            WHERE room_number = :room_number
              AND cleaning_status IN ('Dirty', 'In Progress')
            LIMIT 1
        ");
        $checkTaskStmt->execute([
            ':room_number' => $assigned_room_number
        ]);

        $existingTask = $checkTaskStmt->fetch(PDO::FETCH_ASSOC);

        if (!$existingTask) {
            $insertTaskStmt = $pdo->prepare("
                INSERT INTO housekeeping_tasks (
                    room_number,
                    assigned_staff,
                    cleaning_status,
                    laundry_items,
                    maintenance_needed,
                    housekeeping_notes,
                    laundry_collected,
                    maintenance_reported,
                    room_ready_for_guest,
                    supervisor_reviewed
                ) VALUES (
                    :room_number,
                    :assigned_staff,
                    :cleaning_status,
                    :laundry_items,
                    :maintenance_needed,
                    :housekeeping_notes,
                    :laundry_collected,
                    :maintenance_reported,
                    :room_ready_for_guest,
                    :supervisor_reviewed
                )
            ");

            $insertTaskStmt->execute([
                ':room_number' => $assigned_room_number,
                ':assigned_staff' => 'Unassigned',
                ':cleaning_status' => 'Dirty',
                ':laundry_items' => 0,
                ':maintenance_needed' => 'No',
                ':housekeeping_notes' => 'Auto-created after guest checkout. Room needs cleaning before next guest.',
                ':laundry_collected' => 0,
                ':maintenance_reported' => 0,
                ':room_ready_for_guest' => 0,
                ':supervisor_reviewed' => 0
            ]);
        }
    }

    $logStmt = $pdo->prepare("
        INSERT INTO frontdesk_logs (booking_id, guest_name, room_number, action_type, action_status)
        VALUES (:booking_id, :guest_name, :room_number, 'Check-Out', 'Completed')
    ");
    $logStmt->execute([
        ':booking_id' => $booking['id'],
        ':guest_name' => $guest_name,
        ':room_number' => $assigned_room_number
    ]);

    $pdo->commit();

    jsonResponse(true, 'Check-out completed successfully. Room sent to housekeeping.');
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    jsonResponse(false, 'Failed to complete check-out: ' . $e->getMessage());
}
?>