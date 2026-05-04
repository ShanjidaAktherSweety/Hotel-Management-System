<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/response.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(false, 'Invalid request method.');
}

$booking_id = trim($_POST['booking_id'] ?? '');
$invoice_id = trim($_POST['invoice_id'] ?? '');
$guest_name = trim($_POST['guest_name'] ?? '');
$currency = trim($_POST['currency'] ?? '');
$payment_method = trim($_POST['payment_method'] ?? '');
$room_number = trim($_POST['room_number'] ?? '');
$check_in_date = trim($_POST['check_in_date'] ?? '');
$check_out_date = trim($_POST['check_out_date'] ?? '');
$nights_stayed = (int)($_POST['nights_stayed'] ?? 0);

$room_charge = (float)($_POST['room_charge'] ?? 0);
$activity_charge = (float)($_POST['activity_charge'] ?? 0);
$service_charge = (float)($_POST['service_charge'] ?? 0);
$tax_amount = (float)($_POST['tax_amount'] ?? 0);
$deposit_amount = (float)($_POST['deposit_amount'] ?? 0);
$discount_amount = (float)($_POST['discount_amount'] ?? 0);
$refund_amount = (float)($_POST['refund_amount'] ?? 0);
$invoice_status = trim($_POST['invoice_status'] ?? '');
$billing_notes = trim($_POST['billing_notes'] ?? '');

$add_to_room_bill = isset($_POST['add_to_room_bill']) ? 1 : 0;
$external_customer_invoice = isset($_POST['external_customer_invoice']) ? 1 : 0;
$pre_authorization_required = isset($_POST['pre_authorization_required']) ? 1 : 0;
$auto_generate_final_bill = isset($_POST['auto_generate_final_bill']) ? 1 : 0;

if ($invoice_id === '' || $guest_name === '' || $currency === '' || $payment_method === '' || $invoice_status === '') {
    jsonResponse(false, 'Please fill all required billing fields.');
}

$total_amount = ($room_charge + $activity_charge + $service_charge + $tax_amount) - $deposit_amount - $discount_amount - $refund_amount;

try {
    $checkStmt = $pdo->prepare("SELECT id FROM bills WHERE invoice_id = :invoice_id LIMIT 1");
    $checkStmt->execute([':invoice_id' => $invoice_id]);

    if ($checkStmt->fetch()) {
        jsonResponse(false, 'This invoice ID already exists.');
    }

    $sql = "INSERT INTO bills (
        booking_id, invoice_id, guest_name, currency, payment_method,
        room_number, check_in_date, check_out_date, nights_stayed,
        room_charge, activity_charge, service_charge, tax_amount,
        deposit_amount, discount_amount, refund_amount, total_amount,
        invoice_status, billing_notes,
        add_to_room_bill, external_customer_invoice,
        pre_authorization_required, auto_generate_final_bill
    ) VALUES (
        :booking_id, :invoice_id, :guest_name, :currency, :payment_method,
        :room_number, :check_in_date, :check_out_date, :nights_stayed,
        :room_charge, :activity_charge, :service_charge, :tax_amount,
        :deposit_amount, :discount_amount, :refund_amount, :total_amount,
        :invoice_status, :billing_notes,
        :add_to_room_bill, :external_customer_invoice,
        :pre_authorization_required, :auto_generate_final_bill
    )";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':booking_id' => $booking_id !== '' ? $booking_id : null,
        ':invoice_id' => $invoice_id,
        ':guest_name' => $guest_name,
        ':currency' => $currency,
        ':payment_method' => $payment_method,
        ':room_number' => $room_number !== '' ? $room_number : null,
        ':check_in_date' => $check_in_date !== '' ? $check_in_date : null,
        ':check_out_date' => $check_out_date !== '' ? $check_out_date : null,
        ':nights_stayed' => $nights_stayed,
        ':room_charge' => $room_charge,
        ':activity_charge' => $activity_charge,
        ':service_charge' => $service_charge,
        ':tax_amount' => $tax_amount,
        ':deposit_amount' => $deposit_amount,
        ':discount_amount' => $discount_amount,
        ':refund_amount' => $refund_amount,
        ':total_amount' => $total_amount,
        ':invoice_status' => $invoice_status,
        ':billing_notes' => $billing_notes !== '' ? $billing_notes : null,
        ':add_to_room_bill' => $add_to_room_bill,
        ':external_customer_invoice' => $external_customer_invoice,
        ':pre_authorization_required' => $pre_authorization_required,
        ':auto_generate_final_bill' => $auto_generate_final_bill
    ]);

    jsonResponse(true, 'Invoice saved successfully.');
} catch (Exception $e) {
    jsonResponse(false, 'Failed to save invoice: ' . $e->getMessage());
}
?>