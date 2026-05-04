<?php
require_once 'backend/helpers/role_check.php';
require_once 'backend/helpers/sidebar.php';
requireRole(['Admin', 'Manager', 'Receptionist', 'Accountant', 'Housekeeping', 'Activity Staff']);
$currentPage = 'dashboard.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Hotel Management Dashboard</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body class="dashboard-body">

  <div class="dashboard-layout">

    <!-- Sidebar -->
    <aside class="dashboard-sidebar">
      <div class="sidebar-brand">
        <div class="sidebar-logo">HM</div>
        <div>
          <h2>HotelSys</h2>
          <p>Management System</p>
        </div>
      </div>

      <nav class="sidebar-nav">
        <?php renderSidebar($currentUser, $currentPage); ?>
      </nav>

      <div class="sidebar-footer">
        <form action="logout.php" method="POST">
          <button type="submit" class="logout-btn">Logout</button>
        </form>
      </div>
    </aside>

    <!-- Main Content -->
    <main class="dashboard-main">

      <!-- Top Header -->
      <header class="dashboard-header">
        <div>
          <h1>Welcome to Hotel Management Dashboard</h1>
          <p>Monitor hotel operations, bookings, rooms, services, and staff performance.</p>
        </div>

        <div class="header-actions">
          <div class="search-box">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" placeholder="Search here..." />
          </div>

          <div class="header-icons">
            <button class="icon-btn"><i class="fa-regular fa-bell"></i></button>
            <button class="icon-btn"><i class="fa-regular fa-envelope"></i></button>
          </div>

          <div class="admin-profile">
            <img src="admin.jpg" alt="Admin">
            <div>
              <h4 id="profileName"><?php echo htmlspecialchars($currentUser['username']); ?></h4>
              <p id="profileRole"><?php echo htmlspecialchars($currentUser['role']); ?></p>
            </div>
          </div>
        </div>
      </header>

      <!-- Hero Banner -->
      <section class="hero-banner">
        <div class="hero-text">
          <span class="hero-badge">Live Hotel Overview</span>
          <h2>Manage reservations, rooms, services, and guest activities in one place</h2>
          <p>
            Track room availability, today’s check-ins and check-outs, activity bookings,
            housekeeping, restaurant service, staff scheduling, billing, and reports from a single dashboard.
          </p>
          <div class="hero-buttons">
            <a href="booking.php" class="hero-btn primary">New Booking</a>
            <a href="reports.php" class="hero-btn secondary">View Reports</a>
          </div>
        </div>

        <div class="hero-image">
          <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=1200&q=80" alt="Hotel">
        </div>
      </section>

      <!-- Statistics Cards -->
      <section class="stats-grid">
        <div class="stat-card">
          <div class="stat-icon blue"><i class="fa-solid fa-door-open"></i></div>
          <div>
            <h3>Total Bookings</h3>
            <p id="totalBookingsCount">0</p>
          </div>
        </div>

        <div class="stat-card">
          <div class="stat-icon green"><i class="fa-solid fa-bed"></i></div>
          <div>
            <h3>Occupied Rooms</h3>
            <p id="occupiedRoomsCount">0</p>

          </div>
        </div>

        <div class="stat-card">
          <div class="stat-icon orange"><i class="fa-solid fa-house-circle-check"></i></div>
          <div>
            <h3>Available Rooms</h3>
            <p id="availableRoomsCount">0</p>

          </div>
        </div>

        <div class="stat-card">
          <div class="stat-icon purple"><i class="fa-solid fa-arrow-right-to-bracket"></i></div>
          <div>
            <h3>Today's Check-ins</h3>
            <p id="todayCheckinsCount">0</p>
          </div>
        </div>

        <div class="stat-card">
          <div class="stat-icon red"><i class="fa-solid fa-arrow-right-from-bracket"></i></div>
          <div>
            <h3>Today's Check-outs</h3>
            <p id="todayCheckoutsCount">0</p>
          </div>
        </div>

        <div class="stat-card">
          <div class="stat-icon cyan"><i class="fa-solid fa-chart-pie"></i></div>
          <div>
            <h3>Occupancy Rate</h3>
            <p id="occupancyRateCount">0%</p>
          </div>
        </div>

        <div class="stat-card">
          <div class="stat-icon gold"><i class="fa-solid fa-sack-dollar"></i></div>
          <div>
            <h3>Revenue Today</h3>
            <p id="revenueTodayCount">$0</p>
          </div>
        </div>

        <div class="stat-card">
          <div class="stat-icon teal"><i class="fa-solid fa-person-swimming"></i></div>
          <div>
            <h3>Activity Bookings</h3>
            <p id="activityBookingsCount">0</p>
          </div>
        </div>
      </section>

      <!-- Main Dashboard Content -->
      <section class="dashboard-content-grid">

        <!-- Left Column -->
        <div class="dashboard-left">

          <div class="panel">
            <div class="panel-header">
              <h3>Recent Bookings</h3>
              <a href="booking.php">View All</a>
            </div>

            <div class="table-responsive">
              <table class="dashboard-table">
                <thead>
                  <tr>
                    <th>Guest Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Room Type</th>
                    <th>Check-In</th>
                    <th>Check-Out</th>
                    <th>Guests</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody id="bookingTableBody"></tbody>
              </table>
            </div>
          </div>

          <div class="panel">
            <div class="panel-header">
              <h3>Room Status Overview</h3>
              <a href="rooms.php">Manage Rooms</a>
            </div>

            <div class="room-status-grid">
              <div class="mini-card">
                <h4>Clean Rooms</h4>
                <p id="cleanRoomsCount">0</p>

              </div>
              <div class="mini-card">
                <h4>Dirty Rooms</h4>
                <p id="dirtyRoomsCount">0</p>

              </div>
              <div class="mini-card">
                <h4>Maintenance</h4>
                <p id="maintenanceRoomsCount">0</p>

              </div>
              <div class="mini-card">
                <h4>Reserved</h4>
                <p id="reservedRoomsCount">0</p>
              </div>
            </div>
          </div>

          <div class="panel">
            <div class="panel-header">
              <h3>Activity Booking Overview</h3>
              <a href="activities.php">See Activities</a>
            </div>

            <div class="activity-list">
              <div class="activity-item">
                <div>
                  <h4>Zipline Booking</h4>
                  <p>Time slot bookings and instructor assignment</p>
                </div>
                <span class="activity-count">10</span>
              </div>

              <div class="activity-item">
                <div>
                  <h4>Swimming Session</h4>
                  <p>Pool access scheduling and equipment tracking</p>
                </div>
                <span class="activity-count">8</span>
              </div>

              <div class="activity-item">
                <div>
                  <h4>External Activity Billing</h4>
                  <p>Separate invoicing for non-stay customers</p>
                </div>
                <span class="activity-count">5</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Right Column -->
        <div class="dashboard-right">

          <div class="panel">
            <div class="panel-header">
              <h3>Notifications</h3>
              <a href="#">Clear All</a>
            </div>

            <div class="notification-list" id="dashboardNotificationList">
              <div class="notification-item">
                <i class="fa-solid fa-circle-exclamation"></i>
                <div>
                  <h4>Loading Alerts</h4>
                  <p>Please wait while dashboard alerts are loading.</p>
                </div>
              </div>
            </div>
          </div>

          <div class="panel">
            <div class="panel-header">
              <h3>Quick Access</h3>
            </div>

            <div class="quick-links">
              <a href="booking.php" class="quick-link">Create Booking</a>
              <a href="billing.php" class="quick-link">Generate Bill</a>
              <a href="housekeeping.php" class="quick-link">Update Cleaning</a>
              <a href="restaurant.php" class="quick-link">Room Service</a>
              <a href="staff.php" class="quick-link">Manage Staff</a>
              <a href="reports.php" class="quick-link">Analytics</a>
            </div>
          </div>

          <div class="panel">
            <div class="panel-header">
              <h3>Staff Performance</h3>
              <a href="performance.php">Details</a>
            </div>
            <div class="staff-performance" id="dashboardStaffPerformanceList"></div>

            
          </div>

          <div class="panel">
            <div class="panel-header">
              <h3>Revenue Summary</h3>
            </div>

            <div class="revenue-box">
              <div class="revenue-item">
                <span>Rooms</span>
                <strong id="dashboardRoomRevenue">$0</strong>

              </div>
              <div class="revenue-item">
                <span>Activities</span>
                <strong id="dashboardActivityRevenue">$0</strong>

              </div>
              <div class="revenue-item">
                <span>Restaurant</span>
                <strong id="dashboardServiceRevenue">$0</strong>

              </div>
              <div class="revenue-item">
                <span>Other Services</span>
                <strong id="dashboardOtherRevenue">$0</strong>

              </div>
            </div>
          </div>

        </div>
      </section>

    </main>
  </div>

  <script src="script.js"></script>
  <script>
  async function loadBookings() {
  try {
    const response = await fetch("backend/api/get_bookings.php");
    const result = await response.json();

    if (result.success) {
      const bookings = result.data;
      const tableBody = document.getElementById("bookingTableBody");

      tableBody.innerHTML = "";

      bookings.forEach((booking) => {
        let statusClass = "pending";
        if (booking.booking_status === "Confirmed") {
          statusClass = "confirmed";
        } else if (booking.booking_status === "Cancelled") {
          statusClass = "cancelled";
        }

        const row = `
          <tr>
            <td>${booking.full_name}</td>
            <td>${booking.email}</td>
            <td>${booking.phone}</td>
            <td>${booking.room_type}</td>
            <td>${booking.check_in_date}</td>
            <td>${booking.check_out_date}</td>
            <td>${booking.total_guests}</td>
            <td><span class="status ${statusClass}">${booking.booking_status}</span></td>
          </tr>
        `;

        tableBody.innerHTML += row;
      });
    } else {
      alert("Failed to load bookings");
    }
  } catch (error) {
    console.error(error);
    alert("Server error");
  }
}

async function loadDashboardStaffPerformance() {
  try {
    const response = await fetch("backend/api/get_dashboard_staff_performance.php");
    const result = await response.json();

    const performanceList = document.getElementById("dashboardStaffPerformanceList");
    if (!performanceList) return;

    performanceList.innerHTML = "";

    if (result.success && result.data.length > 0) {
      result.data.forEach((item) => {
        const score = parseInt(item.performance_score || 0, 10);

        const row = `
          <div class="performance-row">
            <span>${item.department}</span>
            <div class="progress"><div style="width: ${score}%;"></div></div>
          </div>
        `;

        performanceList.innerHTML += row;
      });
    } else {
      performanceList.innerHTML = `
        <div class="performance-row">
          <span>No performance data</span>
          <div class="progress"><div style="width: 0%;"></div></div>
        </div>
      `;
    }
  } catch (error) {
    console.error(error);
  }
}

async function loadDashboardStats() {
  try {
    const response = await fetch("backend/api/get_dashboard_overview.php");
    const result = await response.json();

    if (result.success) {
      document.getElementById("totalBookingsCount").textContent = result.data.total_bookings;
      document.getElementById("todayCheckinsCount").textContent = result.data.today_checkins;
      document.getElementById("todayCheckoutsCount").textContent = result.data.today_checkouts;
      document.getElementById("activityBookingsCount").textContent = result.data.activity_bookings;

      document.getElementById("occupiedRoomsCount").textContent = result.data.occupied_rooms;
      document.getElementById("availableRoomsCount").textContent = result.data.available_rooms;
      document.getElementById("occupancyRateCount").textContent = result.data.occupancy_rate + "%";
      document.getElementById("revenueTodayCount").textContent = "$" + parseFloat(result.data.revenue_today).toFixed(2);

      document.getElementById("cleanRoomsCount").textContent = result.data.clean_rooms;
      document.getElementById("dirtyRoomsCount").textContent = result.data.dirty_rooms;
      document.getElementById("maintenanceRoomsCount").textContent = result.data.maintenance_rooms;
      document.getElementById("reservedRoomsCount").textContent = result.data.reserved_rooms;

      document.getElementById("dashboardRoomRevenue").textContent = "$" + parseFloat(result.data.room_revenue).toFixed(2);
      document.getElementById("dashboardActivityRevenue").textContent = "$" + parseFloat(result.data.activity_revenue).toFixed(2);
      document.getElementById("dashboardServiceRevenue").textContent = "$" + parseFloat(result.data.service_revenue).toFixed(2);
      document.getElementById("dashboardOtherRevenue").textContent = "$" + parseFloat(result.data.other_revenue).toFixed(2);
    } else {
      alert("Failed to load dashboard stats");
    }
  } catch (error) {
    console.error(error);
    alert("Server error while loading dashboard stats");
  }
}

async function loadDashboardNotifications() {
  try {
    const response = await fetch("backend/api/get_dashboard_notifications.php");
    const result = await response.json();

    const notificationList = document.getElementById("dashboardNotificationList");
    if (!notificationList) return;

    notificationList.innerHTML = "";

    if (result.success && result.data.length > 0) {
      result.data.forEach((item) => {
        const alertHtml = `
          <div class="notification-item">
            <i class="fa-solid ${item.icon}"></i>
            <div>
              <h4>${item.title}</h4>
              <p>${item.message}</p>
            </div>
          </div>
        `;

        notificationList.innerHTML += alertHtml;
      });
    } else {
      notificationList.innerHTML = `
        <div class="notification-item">
          <i class="fa-solid fa-circle-check"></i>
          <div>
            <h4>No New Notifications</h4>
            <p>All major hotel operations are currently running normally.</p>
          </div>
        </div>
      `;
    }
  } catch (error) {
    console.error(error);
  }
}

loadBookings();
loadDashboardStats();
loadDashboardNotifications();
loadDashboardStaffPerformance();
</script>
</body>
</html>