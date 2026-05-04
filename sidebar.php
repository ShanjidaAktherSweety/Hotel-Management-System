<?php
$sidebarItems = [
    [
        'label' => 'Dashboard',
        'href' => 'dashboard.php',
        'icon' => 'fa-solid fa-house',
        'roles' => ['Admin', 'Manager', 'Receptionist', 'Accountant', 'Housekeeping', 'Activity Staff']
    ],
    [
        'label' => 'Rooms',
        'href' => 'rooms.php',
        'icon' => 'fa-solid fa-bed',
        'roles' => ['Admin', 'Manager', 'Receptionist', 'Housekeeping']
    ],
    [
        'label' => 'Booking',
        'href' => 'booking.php',
        'icon' => 'fa-solid fa-calendar-check',
        'roles' => ['Admin', 'Manager', 'Receptionist']
    ],
    [
        'label' => 'Check-In / Check-Out',
        'href' => 'checkin-checkout.php',
        'icon' => 'fa-solid fa-right-to-bracket',
        'roles' => ['Admin', 'Manager', 'Receptionist']
    ],
    [
        'label' => 'Activities',
        'href' => 'activities.php',
        'icon' => 'fa-solid fa-person-swimming',
        'roles' => ['Admin', 'Manager', 'Activity Staff']
    ],
    [
        'label' => 'Billing',
        'href' => 'billing.php',
        'icon' => 'fa-solid fa-file-invoice-dollar',
        'roles' => ['Admin', 'Manager', 'Accountant']
    ],
    [
        'label' => 'Housekeeping',
        'href' => 'housekeeping.php',
        'icon' => 'fa-solid fa-broom',
        'roles' => ['Admin', 'Manager', 'Housekeeping']
    ],
    [
        'label' => 'Restaurant',
        'href' => 'restaurant.php',
        'icon' => 'fa-solid fa-utensils',
        'roles' => ['Admin', 'Manager','Receptionist']
    ],
    [
        'label' => 'Inventory',
        'href' => 'inventory.php',
        'icon' => 'fa-solid fa-boxes-stacked',
        'roles' => ['Admin', 'Manager']
    ],
    [
        'label' => 'Reports',
        'href' => 'reports.php',
        'icon' => 'fa-solid fa-chart-line',
        'roles' => ['Admin', 'Manager', 'Accountant']
    ],
    [
        'label' => 'Staff Management',
        'href' => 'staff.php',
        'icon' => 'fa-solid fa-users',
        'roles' => ['Admin', 'Manager']
    ],
    [
        'label' => 'Shift Scheduling',
        'href' => 'shift.php',
        'icon' => 'fa-solid fa-clock',
        'roles' => ['Admin', 'Manager']
    ],
    [
        'label' => 'Staff Performance',
        'href' => 'performance.php',
        'icon' => 'fa-solid fa-star',
        'roles' => ['Admin', 'Manager']
    ]
];

function renderSidebar($currentUser, $currentPage) {
    global $sidebarItems;

    foreach ($sidebarItems as $item) {
        if (!in_array($currentUser['role'], $item['roles'])) {
            continue;
        }

        $isActive = ($currentPage === $item['href']) ? 'active' : '';
        echo '<a href="' . htmlspecialchars($item['href']) . '" class="nav-item ' . $isActive . '">';
        echo '<i class="' . htmlspecialchars($item['icon']) . '"></i> ' . htmlspecialchars($item['label']);
        echo '</a>';
    }
}
?>