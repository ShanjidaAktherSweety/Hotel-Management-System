<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/response.php';

try {
    $sql = "SELECT invoice_id, guest_name, total_amount, payment_method, invoice_status
            FROM bills
            ORDER BY id DESC";
    $stmt = $pdo->query($sql);
    $bills = $stmt->fetchAll(PDO::FETCH_ASSOC);

    jsonResponse(true, 'Bills fetched successfully.', $bills);
} catch (Exception $e) {
    jsonResponse(false, 'Failed to fetch bills.');
}
?>