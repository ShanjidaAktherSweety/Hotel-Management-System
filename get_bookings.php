<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/response.php';

try {
    $sql = "SELECT * FROM public_bookings ORDER BY id DESC";
    $stmt = $pdo->query($sql);

    $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

    jsonResponse(true, "Bookings fetched successfully", $bookings);

} catch (Exception $e) {
    jsonResponse(false, "Error fetching bookings");
}