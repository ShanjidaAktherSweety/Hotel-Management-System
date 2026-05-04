<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/response.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(false, 'Invalid request method.');
}
$booking_id = (int)($_POST['booking_id'] ?? 0);
$guest_name = trim($_POST['guest_name'] ?? '');
$activity_type = trim($_POST['activity_type'] ?? '');
$booking_date = trim($_POST['booking_date'] ?? '');
$time_slot = trim($_POST['time_slot'] ?? '');
$guest_weight = !empty($_POST['guest_weight']) ? $_POST['guest_weight'] : null;
$guest_age = !empty($_POST['guest_age']) ? (int)$_POST['guest_age'] : null;
$assigned_staff = trim($_POST['assigned_staff'] ?? '');
$billing_type = trim($_POST['billing_type'] ?? '');
$notes = trim($_POST['notes'] ?? '');

$safety_acknowledged = isset($_POST['safety_acknowledged']) ? 1 : 0;
$equipment_issued = isset($_POST['equipment_issued']) ? 1 : 0;
$external_customer = isset($_POST['external_customer']) ? 1 : 0;
$room_bill_added = isset($_POST['room_bill_added']) ? 1 : 0;

if (
    $guest_name === '' ||
    $activity_type === '' ||
    $booking_date === '' ||
    $time_slot === '' ||
    $assigned_staff === '' ||
    $billing_type === '' ||
    $booking_id <= 0
) {
    jsonResponse(false, 'Please fill all required activity booking fields.');
}

try {
    $pdo->beginTransaction();

    // ✅ STEP 1: Save activity booking
    $sql = "INSERT INTO activity_bookings (
        booking_id, guest_name, activity_type, booking_date, time_slot,
        guest_weight, guest_age, assigned_staff, billing_type,
        notes, safety_acknowledged, equipment_issued,
        external_customer, room_bill_added
    ) VALUES (
        :booking_id, :guest_name, :activity_type, :booking_date, :time_slot,
        :guest_weight, :guest_age, :assigned_staff, :billing_type,
        :notes, :safety_acknowledged, :equipment_issued,
        :external_customer, :room_bill_added
    )";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':booking_id' => $booking_id,
        ':guest_name' => $guest_name,
        ':activity_type' => $activity_type,
        ':booking_date' => $booking_date,
        ':time_slot' => $time_slot,
        ':guest_weight' => $guest_weight,
        ':guest_age' => $guest_age,
        ':assigned_staff' => $assigned_staff,
        ':billing_type' => $billing_type,
        ':notes' => $notes ?: null,
        ':safety_acknowledged' => $safety_acknowledged,
        ':equipment_issued' => $equipment_issued,
        ':external_customer' => $external_customer,
        ':room_bill_added' => $room_bill_added
    ]);

    // ✅ STEP 2: Set activity price
    $priceStmt = $pdo->prepare("
        SELECT price
        FROM activity_items
        WHERE LOWER(TRIM(activity_name)) = LOWER(TRIM(?))
        LIMIT 1
    ");
    $priceStmt->execute([$activity_type]);
    $activity_price = (float)($priceStmt->fetchColumn() ?: 0);

    // ✅ STEP 3: BILLING INTEGRATION BY BOOKING ID
if ($billing_type === 'Add to Room Bill' || $room_bill_added === 1) {

    $billStmt = $pdo->prepare("
        SELECT id, activity_charge, total_amount
        FROM bills
        WHERE booking_id = :booking_id
        AND invoice_status = 'Pending'
        ORDER BY id DESC
        LIMIT 1
    ");

    $billStmt->execute([
        ':booking_id' => $booking_id
    ]);

    $bill = $billStmt->fetch(PDO::FETCH_ASSOC);

    if ($bill) {
        $newActivityCharge = $bill['activity_charge'] + $activity_price;
        $newTotalAmount = $bill['total_amount'] + $activity_price;

        $updateBill = $pdo->prepare("
            UPDATE bills
            SET activity_charge = :activity_charge,
                total_amount = :total_amount,
                billing_notes = CONCAT(IFNULL(billing_notes, ''), :note)
            WHERE id = :id
        ");

        $updateBill->execute([
            ':activity_charge' => $newActivityCharge,
            ':total_amount' => $newTotalAmount,
            ':note' => ' | Activity: ' . $activity_type,
            ':id' => $bill['id']
        ]);
    }
}

    $pdo->commit();

    jsonResponse(true, 'Activity booking saved and billing updated successfully.');

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    jsonResponse(false, 'Database error: ' . $e->getMessage());
}
?>