<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/response.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(false, 'Invalid request method.');
}

$item_name = trim($_POST['item_name'] ?? '');
$category = trim($_POST['category'] ?? '');
$quantity = (int)($_POST['quantity'] ?? 0);
$minimum_stock = (int)($_POST['minimum_stock'] ?? 0);
$unit = trim($_POST['unit'] ?? '');
$supplier_name = trim($_POST['supplier_name'] ?? '');
$reorder_date = trim($_POST['reorder_date'] ?? '');
$deduction_source = trim($_POST['deduction_source'] ?? '');
$inventory_notes = trim($_POST['inventory_notes'] ?? '');

$low_stock_alert = isset($_POST['low_stock_alert']) ? 1 : 0;
$auto_deduct_on_usage = isset($_POST['auto_deduct_on_usage']) ? 1 : 0;
$reorder_reminder_active = isset($_POST['reorder_reminder_active']) ? 1 : 0;
$supplier_notified = isset($_POST['supplier_notified']) ? 1 : 0;

if ($item_name === '' || $category === '' || $unit === '') {
    jsonResponse(false, 'Please fill all required inventory fields.');
}

try {
    $stmt = $pdo->prepare("
        INSERT INTO inventory (
            item_name, category, quantity, minimum_stock, unit,
            supplier_name, reorder_date, deduction_source, inventory_notes,
            low_stock_alert, auto_deduct_on_usage, reorder_reminder_active, supplier_notified
        ) VALUES (
            :item_name, :category, :quantity, :minimum_stock, :unit,
            :supplier_name, :reorder_date, :deduction_source, :inventory_notes,
            :low_stock_alert, :auto_deduct_on_usage, :reorder_reminder_active, :supplier_notified
        )
    ");

    $stmt->execute([
        ':item_name' => $item_name,
        ':category' => $category,
        ':quantity' => $quantity,
        ':minimum_stock' => $minimum_stock,
        ':unit' => $unit,
        ':supplier_name' => $supplier_name !== '' ? $supplier_name : null,
        ':reorder_date' => $reorder_date !== '' ? $reorder_date : null,
        ':deduction_source' => $deduction_source !== '' ? $deduction_source : null,
        ':inventory_notes' => $inventory_notes !== '' ? $inventory_notes : null,
        ':low_stock_alert' => $low_stock_alert,
        ':auto_deduct_on_usage' => $auto_deduct_on_usage,
        ':reorder_reminder_active' => $reorder_reminder_active,
        ':supplier_notified' => $supplier_notified
    ]);

    jsonResponse(true, 'Inventory item saved successfully.');
} catch (Exception $e) {
    jsonResponse(false, 'Failed to save inventory item: ' . $e->getMessage());
}
?>