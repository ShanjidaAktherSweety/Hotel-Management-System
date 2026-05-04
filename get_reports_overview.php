<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/response.php';

try {
    $totalBookings = (int)$pdo->query("SELECT COUNT(*) FROM public_bookings")->fetchColumn();

    $occupiedRooms = (int)$pdo->query("SELECT COUNT(*) FROM rooms WHERE availability_status = 'Occupied'")->fetchColumn();
    $availableRooms = (int)$pdo->query("SELECT COUNT(*) FROM rooms WHERE availability_status = 'Available'")->fetchColumn();
    $totalRooms = (int)$pdo->query("SELECT COUNT(*) FROM rooms")->fetchColumn();

    $occupancyRate = 0;
    if ($totalRooms > 0) {
        $occupancyRate = round(($occupiedRooms / $totalRooms) * 100);
    }

    $totalRevenue = (float)$pdo->query("SELECT COALESCE(SUM(total_amount), 0) FROM bills")->fetchColumn();

    $generatedReports = 24;

    $activityBookings = (int)$pdo->query("SELECT COUNT(*) FROM activity_bookings")->fetchColumn();
    $housekeepingCompleted = (int)$pdo->query("
        SELECT COUNT(*) 
        FROM housekeeping_tasks 
        WHERE cleaning_status IN ('Clean', 'Ready for Inspection')
    ")->fetchColumn();

    $maintenanceRequests = (int)$pdo->query("
        SELECT COUNT(*) 
        FROM rooms 
        WHERE maintenance_status = 'Repair Needed'
    ")->fetchColumn();

    $roomRevenue = (float)$pdo->query("SELECT COALESCE(SUM(room_charge), 0) FROM bills")->fetchColumn();
    $activityRevenue = (float)$pdo->query("SELECT COALESCE(SUM(activity_charge), 0) FROM bills")->fetchColumn();
    $serviceRevenue = (float)$pdo->query("SELECT COALESCE(SUM(service_charge), 0) FROM bills")->fetchColumn();

    jsonResponse(true, 'Reports overview loaded successfully.', [
        'top_stats' => [
            'total_bookings' => $totalBookings,
            'occupancy_rate' => $occupancyRate,
            'total_revenue' => $totalRevenue,
            'generated_reports' => $generatedReports
        ],
        'department_summary' => [
            'room_performance' => "Total rooms: {$totalRooms}, Occupied: {$occupiedRooms}, Available: {$availableRooms}, Occupancy: {$occupancyRate}%",
            'booking_overview' => "Total bookings: {$totalBookings}",
            'activity_report' => "Total activity bookings: {$activityBookings}",
            'billing_report' => "Total revenue: $" . number_format($totalRevenue, 2),
            'housekeeping_report' => "Completed tasks: {$housekeepingCompleted}, Maintenance requests: {$maintenanceRequests}",
            'restaurant_report' => "Restaurant/service revenue: $" . number_format($serviceRevenue, 2)
        ],
        'revenue_breakdown' => [
            'room_revenue' => $roomRevenue,
            'activity_revenue' => $activityRevenue,
            'restaurant_revenue' => $serviceRevenue,
            'other_services' => 0
        ]
    ]);
} catch (Exception $e) {
    jsonResponse(false, 'Failed to load reports overview: ' . $e->getMessage());
}
?>