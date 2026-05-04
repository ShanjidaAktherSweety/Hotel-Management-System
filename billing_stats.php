<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/response.php';

try {
    $totalInvoices = $pdo->query("SELECT COUNT(*) FROM bills")->fetchColumn();
    $paidBills = $pdo->query("SELECT COUNT(*) FROM bills WHERE invoice_status = 'Paid'")->fetchColumn();
    $pendingBills = $pdo->query("SELECT COUNT(*) FROM bills WHERE invoice_status = 'Pending'")->fetchColumn();
    $revenueToday = $pdo->query("SELECT COALESCE(SUM(total_amount), 0) FROM bills WHERE DATE(created_at) = CURDATE()")->fetchColumn();

    jsonResponse(true, 'Billing stats loaded successfully.', [
        'total_invoices' => $totalInvoices,
        'paid_bills' => $paidBills,
        'pending_bills' => $pendingBills,
        'revenue_today' => $revenueToday
    ]);
} catch (Exception $e) {
    jsonResponse(false, 'Failed to load billing stats.');
}
?>