<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/response.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(false, 'Invalid request method.');
}

$full_name = trim($_POST['guestFullName'] ?? '');
$email = trim($_POST['guestEmail'] ?? '');
$phone = trim($_POST['guestPhone'] ?? '');
$country = trim($_POST['guestCountry'] ?? '');
$room_type = trim($_POST['roomType'] ?? '');
$room_count = (int)($_POST['roomCount'] ?? 0);
$check_in_date = trim($_POST['checkIn'] ?? '');
$check_out_date = trim($_POST['checkOut'] ?? '');
$total_guests = (int)($_POST['guests'] ?? 0);
$bed_preference = trim($_POST['bedPreference'] ?? '');
$activity_request = trim($_POST['activitiesSelect'] ?? '');
$activity_date = trim($_POST['activityDate'] ?? '');
$activity_time = trim($_POST['activityTime'] ?? '');
$activity_guests = !empty($_POST['activityGuests']) ? (int)$_POST['activityGuests'] : null;
$restaurant_request = trim($_POST['restaurantService'] ?? '');
$restaurant_date = trim($_POST['restaurantDate'] ?? '');
$restaurant_time = trim($_POST['restaurantTime'] ?? '');
$restaurant_guests = !empty($_POST['restaurantGuests']) ? (int)$_POST['restaurantGuests'] : null;
$special_request = trim($_POST['specialRequest'] ?? '');

if (
    $full_name === '' ||
    $email === '' ||
    $phone === '' ||
    $room_type === '' ||
    $room_count <= 0 ||
    $check_in_date === '' ||
    $check_out_date === '' ||
    $total_guests <= 0
) {
    jsonResponse(false, 'Please fill all required fields correctly.');
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

if (!preg_match('/^[0-9+\- ]+$/', $phone)) {
    jsonResponse(false, 'Invalid phone number.');
}

if ($checkInTimestamp < time()) {
    jsonResponse(false, 'Check-in date cannot be in the past.');
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

    $booking_reference = 'BK-' . time() . rand(100,999);
    // 3. Save booking
    $sql = "INSERT INTO public_bookings (
        full_name,
        email,
        phone,
        country,
        room_type,
        room_count,
        check_in_date,
        check_out_date,
        total_guests,
        bed_preference,
        activity_request,
        activity_date,
        activity_time,
        activity_guests,
        restaurant_request,
        restaurant_date,
        restaurant_time,
        restaurant_guests,
        special_request,
        booking_reference,
        booking_status
    ) VALUES (
        :full_name,
        :email,
        :phone,
        :country,
        :room_type,
        :room_count,
        :check_in_date,
        :check_out_date,
        :total_guests,
        :bed_preference,
        :activity_request,
        :activity_date,
        :activity_time,
        :activity_guests,
        :restaurant_request,
        :restaurant_date,
        :restaurant_time,
        :restaurant_guests,
        :special_request,
        :booking_reference,
        :booking_status
    )";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':full_name' => $full_name,
        ':email' => $email,
        ':phone' => $phone,
        ':country' => $country !== '' ? $country : null,
        ':room_type' => $room_type,
        ':room_count' => $room_count,
        ':check_in_date' => $check_in_date,
        ':check_out_date' => $check_out_date,
        ':total_guests' => $total_guests,
        ':bed_preference' => $bed_preference !== '' ? $bed_preference : null,
        ':activity_request' => $activity_request !== '' ? $activity_request : null,
        ':activity_date' => $activity_date !== '' ? $activity_date : null,
        ':activity_time' => $activity_time !== '' ? $activity_time : null,
        ':activity_guests' => $activity_guests,
        ':restaurant_request' => $restaurant_request !== '' ? $restaurant_request : null,
        ':restaurant_date' => $restaurant_date !== '' ? $restaurant_date : null,
        ':restaurant_time' => $restaurant_time !== '' ? $restaurant_time : null,
        ':restaurant_guests' => $restaurant_guests,
        ':special_request' => $special_request !== '' ? $special_request : null,
        ':booking_reference' => $booking_reference,
        ':booking_status' => 'Pending'
    ]);
    
    $logStmt = $pdo->prepare("
        INSERT INTO frontdesk_logs (guest_name, action_type, action_status)
        VALUES (:guest_name, 'New Booking Request', 'Pending')
    ");
    $logStmt->execute([
        ':guest_name' => $full_name
    ]);
    $booking_id = (int)$pdo->lastInsertId();
    
    $billStmt = $pdo->prepare("
    
        INSERT INTO bills (booking_id, guest_name, invoice_id, invoice_status)
        VALUES (:booking_id, :guest_name, :invoice_id, 'Pending')
    ");
    
    $billStmt->execute([
        ':booking_id' => $booking_id,
        ':guest_name' => $full_name,
        ':invoice_id' => 'INV-' . time()
    ]);

    jsonResponse(true, 'Your booking request has been submitted successfully.');
} catch (Exception $e) {
    jsonResponse(false, 'Failed to submit booking request: ' . $e->getMessage());
}

?>