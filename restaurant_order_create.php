<?php
session_start();

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/response.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(false, 'Invalid request method.');
}
$booking_id = (int)($_POST['booking_id'] ?? 0);
$order_type = trim($_POST['order_type'] ?? '');
$guest_name = trim($_POST['guest_name'] ?? '');
$room_table_ref = trim($_POST['room_table_ref'] ?? '');
$menu_item = trim($_POST['menu_item'] ?? '');
$quantity = (int)($_POST['quantity'] ?? 1);
$kitchen_priority = trim($_POST['kitchen_priority'] ?? '');
$order_status = trim($_POST['order_status'] ?? '');
$kot_number = trim($_POST['kot_number'] ?? '');
$item_charge = (float)($_POST['item_charge'] ?? 0);
$billing_option = trim($_POST['billing_option'] ?? '');
$special_instruction = trim($_POST['special_instruction'] ?? '');

$send_to_kitchen = isset($_POST['send_to_kitchen']) ? 1 : 0;
$add_to_room_bill = isset($_POST['add_to_room_bill']) ? 1 : 0;
$print_kot = isset($_POST['print_kot']) ? 1 : 0;
$notify_service_staff = isset($_POST['notify_service_staff']) ? 1 : 0;

if (
    $order_type === '' ||
    $guest_name === '' ||
    $room_table_ref === '' ||
    $menu_item === '' ||
    $quantity <= 0 ||
    $kitchen_priority === '' ||
    $order_status === '' ||
    $kot_number === '' ||
    $billing_option === '' ||
    $booking_id <= 0
) {
    jsonResponse(false, 'Please fill all required restaurant order fields.');
}

try {
    $pdo->beginTransaction();

    $order_id = 'ORD-' . date('YmdHis');

    // Extract room number (important fix)
    $room_number = preg_replace('/[^0-9]/', '', $room_table_ref);

    // 1. SAVE ORDER
    $stmt = $pdo->prepare("
        INSERT INTO restaurant_orders (
            order_id, booking_id, order_type, guest_name, room_table_ref, menu_item, quantity,
            kitchen_priority, order_status, kot_number, item_charge, billing_option,
            special_instruction, send_to_kitchen, add_to_room_bill, print_kot, notify_service_staff
        ) VALUES (
            :order_id, :booking_id, :order_type, :guest_name, :room_table_ref, :menu_item, :quantity,
            :kitchen_priority, :order_status, :kot_number, :item_charge, :billing_option,
            :special_instruction, :send_to_kitchen, :add_to_room_bill, :print_kot, :notify_service_staff
        )
    ");

    $stmt->execute([
        ':order_id' => $order_id,
        ':booking_id' => $booking_id,
        ':order_type' => $order_type,
        ':guest_name' => $guest_name,
        ':room_table_ref' => $room_table_ref,
        ':menu_item' => $menu_item,
        ':quantity' => $quantity,
        ':kitchen_priority' => $kitchen_priority,
        ':order_status' => $order_status,
        ':kot_number' => $kot_number,
        ':item_charge' => $item_charge,
        ':billing_option' => $billing_option,
        ':special_instruction' => $special_instruction ?: null,
        ':send_to_kitchen' => $send_to_kitchen,
        ':add_to_room_bill' => $add_to_room_bill,
        ':print_kot' => $print_kot,
        ':notify_service_staff' => $notify_service_staff
    ]);

    // 2. BILLING INTEGRATION (IMPROVED)
    if ($billing_option === 'Add to Guest Room Bill' || $add_to_room_bill === 1) {

        $billStmt = $pdo->prepare("
            SELECT id, service_charge, total_amount
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
            $newServiceCharge = $bill['service_charge'] + $item_charge;
            $newTotalAmount = $bill['total_amount'] + $item_charge;

            $updateBillStmt = $pdo->prepare("
                UPDATE bills
                SET service_charge = :service_charge,
                    total_amount = :total_amount,
                    billing_notes = CONCAT(IFNULL(billing_notes, ''), :note)
                WHERE id = :id
            ");

            $updateBillStmt->execute([
                ':service_charge' => $newServiceCharge,
                ':total_amount' => $newTotalAmount,
                ':note' => ' | Restaurant: ' . $menu_item,
                ':id' => $bill['id']
            ]);
        }
    }

    // 3. INVENTORY DEDUCTION (IMPROVED)
    $mapStmt = $pdo->prepare("
        SELECT inventory_item_name, quantity_required
        FROM restaurant_menu_inventory
        WHERE menu_item = :menu_item
        LIMIT 1
    ");
    $mapStmt->execute([':menu_item' => $menu_item]);
    $mapping = $mapStmt->fetch(PDO::FETCH_ASSOC);

    if ($mapping) {
        $requiredQty = $mapping['quantity_required'] * $quantity;

        $updateInventory = $pdo->prepare("
            UPDATE inventory
            SET quantity = GREATEST(quantity - :qty, 0)
            WHERE item_name = :item_name
        ");

        $updateInventory->execute([
            ':qty' => $requiredQty,
            ':item_name' => $mapping['inventory_item_name']
        ]);
    }

    $pdo->commit();

    jsonResponse(true, 'Restaurant order saved successfully.', [
        'order_id' => $order_id,
        'booking_id' => $booking_id
    ]);

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    jsonResponse(false, 'Failed: ' . $e->getMessage());
}
?>