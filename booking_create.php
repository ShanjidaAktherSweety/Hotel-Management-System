<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/response.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(false, 'Invalid request method.');
}

$full_name = trim($_POST['full_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$booking_type = trim($_POST['booking_type'] ?? '');
$check_in_date = trim($_POST['check_in_date'] ?? '');
$check_out_date = trim($_POST['check_out_date'] ?? '');
$room_type = trim($_POST['room_type'] ?? '');
$room_count = (int)($_POST['room_count'] ?? 0);
$adults = (int)($_POST['adults'] ?? 1);
$children = (int)($_POST['children'] ?? 0);
$payment_method = trim($_POST['payment_method'] ?? '');
$deposit = is_numeric($_POST['deposit'] ?? '') ? (float)$_POST['deposit'] : 0;
$special_request = trim($_POST['special_request'] ?? '');

$airport_pickup = isset($_POST['airport_pickup']) ? 1 : 0;
$breakfast_included = isset($_POST['breakfast_included']) ? 1 : 0;
$extra_bed = isset($_POST['extra_bed']) ? 1 : 0;
$flexible_cancellation = isset($_POST['flexible_cancellation']) ? 1 : 0;

$total_guests = $adults + $children;

if (
    $full_name === '' ||
    $email === '' ||
    $phone === '' ||
    $booking_type === '' ||
    $check_in_date === '' ||
    $check_out_date === '' ||
    $room_type === '' ||
    $room_count <= 0 ||
    $payment_method === ''
) {
    jsonResponse(false, 'Please fill all required booking fields.');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    jsonResponse(false, 'Invalid email address.');
}

$checkInTimestamp = strtotime($check_in_date);
$checkOutTimestamp = strtotime($check_out_date);

if ($checkInTimestamp === false || $checkOutTimestamp === false) {
    jsonResponse(false, 'Invalid check-in or check-out date.');
}

if ($checkOutTimestamp <= $checkInTimestamp) {
    jsonResponse(false, 'Check-out date must be after check-in date.');
}

try {
    // 1. Count all usable rooms of the selected type
    $totalRoomsStmt = $pdo->prepare("
        SELECT COUNT(*) 
        FROM rooms
        WHERE room_type = :room_type
          AND (
                maintenance_status IS NULL
                OR maintenance_status NOT IN ('Repair Needed', 'Under Maintenance')
              )
    ");
    $totalRoomsStmt->execute([':room_type' => $room_type]);
    $totalRooms = (int)$totalRoomsStmt->fetchColumn();

    if ($totalRooms <= 0) {
        jsonResponse(false, 'No rooms are configured for the selected room type.');
    }

    // 2. Count already reserved/occupied rooms for overlapping dates
    $bookedRoomsStmt = $pdo->prepare("
        SELECT COALESCE(SUM(room_count), 0)
        FROM public_bookings
        WHERE room_type = :room_type
          AND booking_status IN ('Pending', 'Confirmed', 'Checked In')
          AND check_in_date < :check_out_date
          AND check_out_date > :check_in_date
    ");
    $bookedRoomsStmt->execute([
        ':room_type' => $room_type,
        ':check_in_date' => $check_in_date,
        ':check_out_date' => $check_out_date
    ]);
    $bookedRooms = (int)$bookedRoomsStmt->fetchColumn();

    $availableRooms = $totalRooms - $bookedRooms;

    if ($availableRooms < $room_count) {
        $left = max($availableRooms, 0);
        jsonResponse(false, "Selected room type is not available for the requested dates. Only {$left} room(s) left.");
    }

    // 3. Save booking
    $sql = "INSERT INTO public_bookings (
        full_name,
        email,
        phone,
        room_type,
        room_count,
        check_in_date,
        check_out_date,
        total_guests,
        booking_type,
        adults,
        children,
        payment_method,
        deposit,
        airport_pickup,
        breakfast_included,
        extra_bed,
        flexible_cancellation,
        special_request,
        booking_status
    ) VALUES (
        :full_name,
        :email,
        :phone,
        :room_type,
        :room_count,
        :check_in_date,
        :check_out_date,
        :total_guests,
        :booking_type,
        :adults,
        :children,
        :payment_method,
        :deposit,
        :airport_pickup,
        :breakfast_included,
        :extra_bed,
        :flexible_cancellation,
        :special_request,
        :booking_status
    )";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':full_name' => $full_name,
        ':email' => $email,
        ':phone' => $phone,
        ':room_type' => $room_type,
        ':room_count' => $room_count,
        ':check_in_date' => $check_in_date,
        ':check_out_date' => $check_out_date,
        ':total_guests' => $total_guests,
        ':booking_type' => $booking_type,
        ':adults' => $adults,
        ':children' => $children,
        ':payment_method' => $payment_method,
        ':deposit' => $deposit,
        ':airport_pickup' => $airport_pickup,
        ':breakfast_included' => $breakfast_included,
        ':extra_bed' => $extra_bed,
        ':flexible_cancellation' => $flexible_cancellation,
        ':special_request' => $special_request !== '' ? $special_request : null,
        ':booking_status' => 'Pending'
    ]);

    jsonResponse(true, 'Booking saved successfully.');
} catch (Exception $e) {
    jsonResponse(false, 'Failed to save booking: ' . $e->getMessage());
}
?>