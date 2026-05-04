<?php
require_once 'backend/helpers/role_check.php';
require_once 'backend/helpers/sidebar.php';
requireRole(['Admin', 'Manager','Accountant']);
$currentPage = 'reports.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Hotel Management System - Reports</title>
  <link rel="stylesheet" href="style.css" />
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

    <!-- Main -->
    <main class="dashboard-main">

      <!-- Header -->
      <header class="dashboard-header">
        <div>
          <h1>Reports & Analytics</h1>
          <p>Monitor hotel performance through booking, room, activity, billing, housekeeping, restaurant, and inventory reports.</p>
        </div>

        <div class="header-actions">
          <div class="search-box">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" placeholder="Search report or metric..." />
          </div>
          <button type="button" class="hero-btn primary">+ Export Report</button>
        </div>
      </header>

      <!-- Top Stats -->
      <section class="stats-grid reports-stats">
        <div class="stat-card">
          <div class="stat-icon blue"><i class="fa-solid fa-calendar-days"></i></div>
          <div>
            <h3>Total Bookings</h3>
            <p id="reportsTotalBookings">0</p>

          </div>
        </div>

        <div class="stat-card">
          <div class="stat-icon green"><i class="fa-solid fa-chart-pie"></i></div>
          <div>
            <h3>Occupancy Rate</h3>
            <p id="reportsOccupancyRate">0%</p>

          </div>
        </div>

        <div class="stat-card">
          <div class="stat-icon gold"><i class="fa-solid fa-sack-dollar"></i></div>
          <div>
            <h3>Total Revenue</h3>
            <p id="reportsTotalRevenue">$0</p>

          </div>
        </div>

        <div class="stat-card">
          <div class="stat-icon purple"><i class="fa-solid fa-file-lines"></i></div>
          <div>
            <h3>Generated Reports</h3>
            <p id="reportsGeneratedReports">0</p>
          </div>
        </div>
      </section>

      <!-- Filters -->
      <section class="panel reports-filter-panel">
        <div class="panel-header">
          <h3>Report Filters</h3>
        </div>

        <div class="reports-filters">
          <select id="reportPeriodSelect">
            <option>This Week</option>
            <option>This Month</option>
            <option>This Quarter</option>
            <option>This Year</option>
          </select>

          <select id="reportDepartmentSelect">
            <option>All Departments</option>
            <option>Rooms</option>
            <option>Booking</option>
            <option>Activities</option>
            <option>Billing</option>
            <option>Housekeeping</option>
            <option>Restaurant</option>
            <option>Inventory</option>
          </select>

          <select id="reportViewSelect">
            <option>Summary View</option>
            <option>Detailed View</option>
            <option>Performance View</option>
            <option>Financial View</option>
          </select>

          <button type="button" class="filter-btn" id="generateReportBtn">Generate Report</button>
        </div>
      </section>

      <section class="dashboard-content-grid reports-grid">

        <!-- Left -->
        <div class="dashboard-left">

          <div class="panel">
            <div class="panel-header">
              <h3>Department Performance Summary</h3>
              <a href="#">Download</a>
            </div>

            <div class="reports-summary-grid">
              <div class="report-summary-card">
                <h4>Room Performance</h4>
                <p id="reportRoomPerformance">Loading...</p>
              </div>

              <div class="report-summary-card">
                <h4>Booking Overview</h4>
                <p id="reportBookingOverview">Loading...</p>
              </div>

              <div class="report-summary-card">
                <h4>Activity Report</h4>
                <p id="reportActivityOverview">Loading...</p>
              </div>

              <div class="report-summary-card">
                <h4>Billing Report</h4>
                <p id="reportBillingOverview">Loading...</p>
              </div>

              <div class="report-summary-card">
                <h4>Housekeeping Report</h4>
                <p id="reportHousekeepingOverview">Loading...</p>
              </div>

              <div class="report-summary-card">
                <h4>Restaurant Report</h4>
                <p id="reportRestaurantOverview">Loading...</p>
              </div>
            </div>
          </div>

          <div class="panel">
            <div class="panel-header">
              <h3>Revenue Breakdown</h3>
              <a href="#">Financial View</a>
            </div>

            <div class="revenue-box">
              <div class="revenue-item">
                <span>Room Revenue</span>
                <strong id="roomRevenueValue">$0</strong>

              </div>
              <div class="revenue-item">
                <span>Activity Revenue</span>
                <strong id="activityRevenueValue">$0</strong>

              </div>
              <div class="revenue-item">
                <span>Restaurant Revenue</span>
                <strong id="restaurantRevenueValue">$0</strong>

              </div>
              <div class="revenue-item">
                <span>Other Services</span>
                <strong id="otherServicesRevenueValue">$0</strong>
              </div>
            </div>
          </div>

          <div class="panel">
            <div class="panel-header">
              <h3>Generated Report Table</h3>
              <a href="#">View All</a>
            </div>

            <div class="table-responsive">
              <table class="dashboard-table">
                <thead>
                  <tr>
                    <th>Report ID</th>
                    <th>Department</th>
                    <th>Period</th>
                    <th>Status</th>
                    <th>Format</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody id="generatedReportsTableBody"></tbody>
                
              </table>
            </div>
          </div>

        </div>

        <!-- Right -->
        <div class="dashboard-right">

          <div class="panel">
            <div class="panel-header">
              <h3>Key Insights</h3>
            </div>

            <div class="assignment-list">
              <div class="assignment-item">
                <h4>High Occupancy</h4>
                <p>Weekend bookings increased room occupancy significantly.</p>
              </div>
              <div class="assignment-item">
                <h4>Top Activity</h4>
                <p>Zipline remains the most requested guest activity.</p>
              </div>
              <div class="assignment-item">
                <h4>Billing Success</h4>
                <p>Most invoices were settled on time with fewer pending payments.</p>
              </div>
              <div class="assignment-item">
                <h4>Inventory Alert</h4>
                <p>Bath towels and restaurant stock require quick replenishment.</p>
              </div>
            </div>
          </div>

          <div class="panel">
            <div class="panel-header">
              <h3>Top Performing Areas</h3>
            </div>

            <div class="staff-performance">
              <div class="performance-row">
                <span>Rooms</span>
                <div class="progress"><div style="width: 88%;"></div></div>
              </div>
              <div class="performance-row">
                <span>Restaurant</span>
                <div class="progress"><div style="width: 84%;"></div></div>
              </div>
              <div class="performance-row">
                <span>Activities</span>
                <div class="progress"><div style="width: 76%;"></div></div>
              </div>
              <div class="performance-row">
                <span>Housekeeping</span>
                <div class="progress"><div style="width: 72%;"></div></div>
              </div>
            </div>
          </div>

          <div class="panel">
            <div class="panel-header">
              <h3>Quick Reports</h3>
            </div>

            <div class="quick-links">
              <a href="#" class="quick-link">Daily Revenue</a>
              <a href="#" class="quick-link">Occupancy Report</a>
              <a href="#" class="quick-link">Booking Trends</a>
              <a href="#" class="quick-link">Activity Summary</a>
              <a href="#" class="quick-link">Inventory Status</a>
              <a href="#" class="quick-link">Restaurant Sales</a>
            </div>
          </div>

          <div class="panel">
            <div class="panel-header">
              <h3>Alerts & Recommendations</h3>
            </div>

            <div class="notification-list">
              <div class="notification-item">
                <i class="fa-solid fa-chart-line"></i>
                <div>
                  <h4>Revenue Growth</h4>
                  <p>Total revenue is performing well compared to the previous week.</p>
                </div>
              </div>

              <div class="notification-item">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <div>
                  <h4>Inventory Restock Needed</h4>
                  <p>Low stock items may affect housekeeping and restaurant operations.</p>
                </div>
              </div>

              <div class="notification-item">
                <i class="fa-solid fa-broom"></i>
                <div>
                  <h4>Cleaning Efficiency</h4>
                  <p>Housekeeping needs faster turnaround for inspection-ready rooms.</p>
                </div>
              </div>
            </div>
          </div>

        </div>
      </section>

    </main>
  </div>


<script src="script.js"></script>
<script>
const reportPeriodSelect = document.getElementById("reportPeriodSelect");
const reportDepartmentSelect = document.getElementById("reportDepartmentSelect");
const reportViewSelect = document.getElementById("reportViewSelect");
const generateReportBtn = document.getElementById("generateReportBtn");
async function loadReportsOverview() {
  try {
    const response = await fetch("backend/api/get_reports_overview.php");
    const result = await response.json();

    if (result.success) {
      document.getElementById("reportsTotalBookings").textContent = result.data.top_stats.total_bookings;
      document.getElementById("reportsOccupancyRate").textContent = result.data.top_stats.occupancy_rate + "%";
      document.getElementById("reportsTotalRevenue").textContent = "$" + parseFloat(result.data.top_stats.total_revenue).toFixed(2);
      document.getElementById("reportsGeneratedReports").textContent = result.data.top_stats.generated_reports;

      document.getElementById("reportRoomPerformance").textContent = result.data.department_summary.room_performance;
      document.getElementById("reportBookingOverview").textContent = result.data.department_summary.booking_overview;
      document.getElementById("reportActivityOverview").textContent = result.data.department_summary.activity_report;
      document.getElementById("reportBillingOverview").textContent = result.data.department_summary.billing_report;
      document.getElementById("reportHousekeepingOverview").textContent = result.data.department_summary.housekeeping_report;
      document.getElementById("reportRestaurantOverview").textContent = result.data.department_summary.restaurant_report;

      document.getElementById("roomRevenueValue").textContent = "$" + parseFloat(result.data.revenue_breakdown.room_revenue).toFixed(2);
      document.getElementById("activityRevenueValue").textContent = "$" + parseFloat(result.data.revenue_breakdown.activity_revenue).toFixed(2);
      document.getElementById("restaurantRevenueValue").textContent = "$" + parseFloat(result.data.revenue_breakdown.restaurant_revenue).toFixed(2);
      document.getElementById("otherServicesRevenueValue").textContent = "$" + parseFloat(result.data.revenue_breakdown.other_services).toFixed(2);
    } else {
      alert(result.message);
    }
  } catch (error) {
    console.error(error);
    alert("Server error while loading reports overview.");
  }
}

async function loadGeneratedReports() {
  try {
    const response = await fetch("backend/api/get_generated_reports.php");
    const result = await response.json();

    if (result.success) {
      const tableBody = document.getElementById("generatedReportsTableBody");
      tableBody.innerHTML = "";

      result.data.forEach((report) => {
        let statusClass = "confirmed";
        if (report.report_status === "Generated") statusClass = "checked";
        if (report.report_status === "Pending") statusClass = "pending";

        const row = `
          <tr>
            <td>${report.report_id}</td>
            <td>${report.department}</td>
            <td>${report.period_label}</td>
            <td><span class="status ${statusClass}">${report.report_status}</span></td>
            <td>${report.report_format}</td>
            <td><a href="report-view.php?report_id=${encodeURIComponent(report.report_id)}" class="table-link">View</a></td>
          </tr>
        `;

        tableBody.innerHTML += row;
      });
    }
  } catch (error) {
    console.error(error);
  }
}
if (generateReportBtn) {
  generateReportBtn.addEventListener("click", async function () {
    const formData = new FormData();
    formData.append("department", reportDepartmentSelect ? reportDepartmentSelect.value : "All Departments");
    formData.append("period_label", reportPeriodSelect ? reportPeriodSelect.value : "Current Data");
    formData.append("report_format", reportViewSelect ? reportViewSelect.value : "Live");

    try {
      const response = await fetch("backend/api/generate_report.php", {
        method: "POST",
        body: formData
      });

      const result = await response.json();

      if (result.success) {
        alert(`${result.message} (${result.data.report_id})`);
        loadGeneratedReports();
      } else {
        alert(result.message);
      }
    } catch (error) {
      console.error(error);
      alert("Server error while generating report.");
    }
  });
}

loadReportsOverview();
loadGeneratedReports();
</script>
  
</body>
</html>