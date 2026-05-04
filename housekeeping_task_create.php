<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/response.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(false, 'Invalid request method.');
}

$room_id = trim($_POST['room_id'] ?? '');
$assigned_staff = trim($_POST['assigned_staff'] ?? '');
$cleaning_status = trim($_POST['cleaning_status'] ?? '');
$priority_level = trim($_POST['priority_level'] ?? '');
$laundry_items = (int)($_POST['laundry_items'] ?? 0);
$maintenance_needed = trim($_POST['maintenance_needed'] ?? '');
$housekeeping_notes = trim($_POST['housekeeping_notes'] ?? '');

$laundry_collected = isset($_POST['laundry_collected']) ? 1 : 0;
$maintenance_reported = isset($_POST['maintenance_reported']) ? 1 : 0;
$room_ready_for_guest = isset($_POST['room_ready_for_guest']) ? 1 : 0;
$supervisor_reviewed = isset($_POST['supervisor_reviewed']) ? 1 : 0;

if ($room_id === '' || $assigned_staff === '' || $cleaning_status === '' || $priority_level === '') {
    jsonResponse(false, 'Please fill all required housekeeping fields.');
}

try {
    $roomStmt = $pdo->prepare("SELECT room_number FROM rooms WHERE id = :id LIMIT 1");
    $roomStmt->execute([':id' => $room_id]);
    $room = $roomStmt->fetch(PDO::FETCH_ASSOC);

    if (!$room) {
        jsonResponse(false, 'Selected room not found.');
    }

    $room_number = $room['room_number'];

    $insertSql = "INSERT INTO housekeeping_tasks (
        room_id, room_number, assigned_staff, cleaning_status, priority_level,
        laundry_items, maintenance_needed, housekeeping_notes,
        laundry_collected, maintenance_reported, room_ready_for_guest, supervisor_reviewed
    ) VALUES (
        :room_id, :room_number, :assigned_staff, :cleaning_status, :priority_level,
        :laundry_items, :maintenance_needed, :housekeeping_notes,
        :laundry_collected, :maintenance_reported, :room_ready_for_guest, :supervisor_reviewed
    )";

    $stmt = $pdo->prepare($insertSql);
    $stmt->execute([
        ':room_id' => $room_id,
        ':room_number' => $room_number,
        ':assigned_staff' => $assigned_staff,
        ':cleaning_status' => $cleaning_status,
        ':priority_level' => $priority_level,
        ':laundry_items' => $laundry_items,
        ':maintenance_needed' => $maintenance_needed !== '' ? $maintenance_needed : null,
        ':housekeeping_notes' => $housekeeping_notes !== '' ? $housekeeping_notes : null,
        ':laundry_collected' => $laundry_collected,
        ':maintenance_reported' => $maintenance_reported,
        ':room_ready_for_guest' => $room_ready_for_guest,
        ':supervisor_reviewed' => $supervisor_reviewed
    ]);

    // Update the main rooms table too
    $newAvailability = 'Available';
    if ($cleaning_status === 'Dirty' || $cleaning_status === 'Under Cleaning') {
        $newAvailability = 'Available';
    }

    $roomUpdateSql = "UPDATE rooms
                      SET cleaning_status = :cleaning_status,
                          availability_status = :availability_status
                      WHERE id = :id";

    $updateStmt = $pdo->prepare($roomUpdateSql);
    $updateStmt->execute([
        ':cleaning_status' => $cleaning_status === 'Ready for Inspection' ? 'Clean' : $cleaning_status,
        ':availability_status' => ($cleaning_status === 'Clean' || $cleaning_status === 'Ready for Inspection') ? 'Available' : 'Available',
        ':id' => $room_id
    ]);

    if ($maintenance_needed !== '' && $maintenance_needed !== 'No') {
        $maintenanceStmt = $pdo->prepare("
            UPDATE rooms
            SET maintenance_status = 'Repair Needed'
            WHERE id = :id
        ");
        $maintenanceStmt->execute([':id' => $room_id]);
    }

    jsonResponse(true, 'Housekeeping task updated successfully.');
} catch (Exception $e) {
    jsonResponse(false, 'Failed to save housekeeping task: ' . $e->getMessage());
}
?>