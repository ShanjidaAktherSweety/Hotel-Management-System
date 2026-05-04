<?php
require_once 'backend/helpers/role_check.php';
require_once 'backend/helpers/sidebar.php';
requireRole(['Admin', 'Manager']);
$currentPage = 'performance.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Hotel Management System - Staff Performance</title>
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

      <form action="logout.php" method="POST">
        <button type="submit" class="logout-btn">Logout</button>
      </form>
    </aside>

    <!-- Main -->
    <main class="dashboard-main">

      <!-- Header -->
      <header class="dashboard-header">
        <div>
          <h1>Staff Performance</h1>
          <p>Track employee productivity, attendance, punctuality, task completion, service quality, and department performance.</p>
        </div>

        <div class="header-actions">
          <div class="search-box">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" placeholder="Search staff, role, or department..." />
          </div>
          <button type="button" class="hero-btn primary">+ Generate Review</button>
        </div>
      </header>

      <!-- Top Stats -->
      <section class="stats-grid performance-stats">
        <div class="stat-card">
          <div class="stat-icon blue"><i class="fa-solid fa-user-check"></i></div>
          <div>
            <h3>Top Performers</h3>
            <p id="topPerformersCount">0</p>
          </div>
        </div>

        <div class="stat-card">
          <div class="stat-icon green"><i class="fa-solid fa-clipboard-check"></i></div>
          <div>
            <h3>Task Completion</h3>
            <p id="taskCompletionCount">0%</p>
          </div>
        </div>

        <div class="stat-card">
          <div class="stat-icon orange"><i class="fa-solid fa-clock"></i></div>
          <div>
            <h3>Punctuality Rate</h3>
            <p id="punctualityRateCount">0%</p>
          </div>
        </div>

        <div class="stat-card">
          <div class="stat-icon purple"><i class="fa-solid fa-medal"></i></div>
          <div>
            <h3>Avg. Rating</h3>
            <p id="avgRatingCount">0 / 5</p>
          </div>
        </div>
      </section>

      <!-- Filters -->
      <section class="panel performance-filter-panel">
        <div class="panel-header">
          <h3>Performance Filters</h3>
        </div>

        <div class="performance-filters">
          <select>
            <option>All Departments</option>
            <option>Front Desk</option>
            <option>Housekeeping</option>
            <option>Restaurant</option>
            <option>Activities</option>
            <option>Finance</option>
            <option>Operations</option>
          </select>

          <select>
            <option>This Month</option>
            <option>This Week</option>
            <option>This Quarter</option>
            <option>This Year</option>
          </select>

          <select>
            <option>All Ratings</option>
            <option>Excellent</option>
            <option>Good</option>
            <option>Average</option>
            <option>Needs Improvement</option>
          </select>

          <button type="button" class="filter-btn">Apply Filter</button>
        </div>
      </section>

      <section class="dashboard-content-grid performance-grid">

        <!-- Left -->
        <div class="dashboard-left">

          <div class="panel">
            <div class="panel-header">
              <h3>Performance Review Form</h3>
              <a href="#">Save Draft</a>
            </div>

            <form class="performance-form" id="performanceForm">
              <div class="form-grid">
                <div class="form-group">
                  <label for="employeeName">Employee Name</label>
                  <select id="employeeName" name="staff_name" required>
                    <option value="">Select employee</option>
                  </select>
                  
                </div>

                <div class="form-group">
                  <label for="employeeDepartment">Department</label>
                  <select id="employeeDepartment" name="department" required>
                    <option value="">Select department</option>
                    <option>Front Desk</option>
                    <option>Housekeeping</option>
                    <option>Restaurant</option>
                    <option>Activities</option>
                    <option>Finance</option>
                    <option>Operations</option>
                  </select>
                </div>

                <div class="form-group">
                  <label for="reviewDate">Review Date</label>
                  <input type="date" id="reviewDate" name="review_date" required />
                </div>

                <div class="form-group">
                  <label for="reviewPeriod">Review Period</label>
                  <select id="reviewPeriod" name="review_period" required>
                    <option value="">Select review period</option>
                    <option>Weekly</option>
                    <option>Monthly</option>
                    <option>Quarterly</option>
                  </select>
                </div>

                <div class="form-group">
                  <label for="taskCompletion">Task Completion (%)</label>
                  <input type="number" id="taskCompletion" name="task_completion" placeholder="Enter completion percentage" required />
                </div>

                <div class="form-group">
                  <label for="attendanceRate">Attendance Rate (%)</label>
                  <input type="number" id="attendanceRate" name="attendance_rate" placeholder="Enter attendance rate" required />
                </div>

                <div class="form-group">
                  <label for="serviceQuality">Service Quality Rating</label>
                  <select id="serviceQuality" name="service_quality" required>
                    <option value="">Select rating</option>
                    <option>Excellent</option>
                    <option>Good</option>
                    <option>Average</option>
                    <option>Needs Improvement</option>
                  </select>
                </div>

                <div class="form-group">
                  <label for="punctuality">Punctuality</label>
                  <select id="punctuality" name="punctuality"  required>
                    <option value="">Select punctuality</option>
                    <option>Excellent</option>
                    <option>Good</option>
                    <option>Average</option>
                    <option>Poor</option>
                  </select>
                </div>

                <div class="form-group">
                  <label for="overallRating">Overall Rating</label>
                  <select id="overallRating" name="overall_rating" required>
                    <option value="">Select overall rating</option>
                    <option>5 - Excellent</option>
                    <option>4 - Good</option>
                    <option>3 - Average</option>
                    <option>2 - Needs Improvement</option>
                    <option>1 - Poor</option>
                  </select>
                </div>

                <div class="form-group full-width">
                  <label for="reviewNotes">Review Notes</label>
                  <textarea id="reviewNotes" name="review_notes" rows="4" placeholder="Add performance comments, achievements, suggestions, or improvement notes"></textarea>
                </div>
              </div>

              <div class="booking-checkbox-row">
                <label><input type="checkbox" name="eligible_for_reward" /> Eligible for Reward</label>
                <label><input type="checkbox" name="promotion_recommended" /> Promotion Recommended</label>
                <label><input type="checkbox" name="needs_training"/> Needs Training</label>
                <label><input type="checkbox" name="review_completed" /> Review Completed</label>
              </div>

              <div class="booking-form-actions">
                <button type="reset" class="secondary-btn-small room-btn">Reset</button>
                <button type="submit" class="primary-btn-small room-btn">Save Review</button>
              </div>
            </form>
          </div>

          <div class="panel">
            <div class="panel-header">
              <h3>Staff Performance Table</h3>
              <a href="#">View All</a>
            </div>

            <div class="table-responsive">
              <table class="dashboard-table">
                <thead>
                  <tr>
                    <th>Name</th>
                    <th>Department</th>
                    <th>Attendance</th>
                    <th>Tasks</th>
                    <th style="min-width: 130px;">Rating</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody id="performanceTableBody"></tbody>
                
              </table>
            </div>
          </div>

        </div>

        <!-- Right -->
        <div class="dashboard-right">

          <div class="panel">
            <div class="panel-header">
              <h3>Department Performance</h3>
            </div>

            <div class="department-pie-wrapper">
              <canvas id="departmentPieChart" width="260" height="260"></canvas>
            </div>
            <div id="departmentPerformanceLegend"></div>

            
          </div>

          <div class="panel">
            <div class="panel-header">
              <h3>Top Performer Highlights</h3>
            </div>
            <div class="assignment-list" id="topPerformerHighlightsList"></div>

            
          </div>

          <div class="panel">
            <div class="panel-header">
              <h3>Quick Actions</h3>
            </div>

            <div class="quick-links">
              <a href="#" class="quick-link">Monthly Review</a>
              <a href="#" class="quick-link">Reward List</a>
              <a href="#" class="quick-link">Training Needs</a>
              <a href="#" class="quick-link">Attendance Log</a>
              <a href="#" class="quick-link">Performance Export</a>
              <a href="#" class="quick-link">Department Ranking</a>
            </div>
          </div>

          <div class="panel">
            <div class="panel-header">
              <h3>Performance Alerts</h3>
            </div>

            <div class="notification-list">
              <div class="notification-item">
                <i class="fa-solid fa-medal"></i>
                <div>
                  <h4>Top Performer Identified</h4>
                  <p>Two staff members qualify for monthly recognition and rewards.</p>
                </div>
              </div>

              <div class="notification-item">
                <i class="fa-solid fa-user-graduate"></i>
                <div>
                  <h4>Training Recommended</h4>
                  <p>One employee needs additional training for service quality improvement.</p>
                </div>
              </div>

              <div class="notification-item">
                <i class="fa-solid fa-chart-line"></i>
                <div>
                  <h4>Department Growth</h4>
                  <p>Front desk and restaurant teams improved performance this month.</p>
                </div>
              </div>
            </div>
          </div>

        </div>
      </section>

    </main>
  </div>

<script src="script.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const performanceForm = document.getElementById("performanceForm");

async function loadPerformanceStaffDropdown() {
  try {
    const response = await fetch("backend/api/get_staff_dropdown.php");
    const result = await response.json();

    const employeeSelect = document.getElementById("employeeName");
    if (!employeeSelect) return;

    employeeSelect.innerHTML = '<option value="">Select employee</option>';

    if (result.success) {
      result.data.forEach((staff) => {
        const option = document.createElement("option");
        option.value = staff.full_name;
        option.textContent = `${staff.full_name} (${staff.role})`;
        option.dataset.department = staff.department;
        employeeSelect.appendChild(option);
      });
    }
  } catch (error) {
    console.error(error);
  }
}

function autoFillPerformanceDepartment() {
  const employeeSelect = document.getElementById("employeeName");
  const selectedOption = employeeSelect.options[employeeSelect.selectedIndex];

  if (!selectedOption || !selectedOption.value) return;

  document.getElementById("employeeDepartment").value = selectedOption.dataset.department || "";
}

async function loadPerformanceRecords() {
  try {
    const response = await fetch("backend/api/get_performance_records.php");
    const result = await response.json();

    const tableBody = document.getElementById("performanceTableBody");
    if (!tableBody) return;

    tableBody.innerHTML = "";

    if (result.success) {
      if (!result.data || result.data.length === 0) {
        tableBody.innerHTML = `
          <tr>
            <td colspan="6" style="text-align:center;">No performance records found.</td>
          </tr>
        `;
        return;
      }

      result.data.forEach((record) => {
        let badgeClass = "average";
        let statusText = "Stable";

        if (record.overall_rating.startsWith("5")) {
          badgeClass = "excellent";
          statusText = "Top Performer";
        } else if (record.overall_rating.startsWith("4")) {
          badgeClass = "good";
          statusText = "Good";
        } else if (record.overall_rating.startsWith("2") || record.overall_rating.startsWith("1")) {
          badgeClass = "improvement";
          statusText = "Needs Improvement";
        }

        const row = `
          <tr>
            <td>${record.staff_name}</td>
            <td>${record.department}</td>
            <td>${record.attendance_rate}%</td>
            <td>${record.task_completion}%</td>
            <td><span class="performance-badge ${badgeClass}">${record.overall_rating}</span></td>
            <td>${statusText}</td>
          </tr>
        `;
        tableBody.innerHTML += row;
      });
    }
  } catch (error) {
    console.error(error);
  }
}

let departmentPieChart = null;

async function loadDepartmentPerformance() {
  try {
    const response = await fetch("backend/api/performance_department_summary.php");
    const result = await response.json();

    const canvas = document.getElementById("departmentPieChart");
    const legend = document.getElementById("departmentPerformanceLegend");

    if (!canvas || !legend) return;

    if (!result.success || !result.data || result.data.length === 0) {
      legend.innerHTML = "<p>No department data available.</p>";
      return;
    }

    const labels = result.data.map(item => item.department);
    const scores = result.data.map(item => parseInt(item.department_score || 0, 10));

    const colors = [
      "#06b6d4",
      "#3b82f6",
      "#f59e0b",
      "#84cc16",
      "#64748b",
      "#8b5cf6"
    ];

    if (departmentPieChart) {
      departmentPieChart.destroy();
    }

    departmentPieChart = new Chart(canvas, {
      type: "pie",
      data: {
        labels: labels,
        datasets: [{
          data: scores,
          backgroundColor: colors,
          borderColor: "#ffffff",
          borderWidth: 2
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            display: false
          },
          tooltip: {
            callbacks: {
              label: function(context) {
                return `${context.label}: ${context.raw}%`;
              }
            }
          }
        }
      }
    });

    legend.innerHTML = labels.map((label, index) => `
      <div class="department-pie-legend-item">
        <span style="background:${colors[index % colors.length]}"></span>
        <p>${label}</p>
        <strong>${scores[index]}%</strong>
      </div>
    `).join("");

  } catch (error) {
    console.error(error);
  }
}
async function loadTopPerformerHighlights() {
  try {
    const response = await fetch("backend/api/top_performer_highlights.php");
    const result = await response.json();

    const highlightsList = document.getElementById("topPerformerHighlightsList");
    if (!highlightsList) return;

    highlightsList.innerHTML = "";

    if (result.success) {
      if (!result.data || result.data.length === 0) {
        highlightsList.innerHTML = `
          <div class="assignment-item">
            <h4>No top performer yet</h4>
            <p>No performance reviews available right now.</p>
          </div>
        `;
        return;
      }

      result.data.forEach((item) => {
        const noteText = item.review_notes && item.review_notes.trim() !== ""
          ? item.review_notes
          : `${item.staff_name} performed strongly in ${item.department} with rating ${item.overall_rating}.`;

        const row = `
          <div class="assignment-item">
            <h4>${item.staff_name}</h4>
            <p>${noteText}</p>
          </div>
        `;

        highlightsList.innerHTML += row;
      });
    } else {
      highlightsList.innerHTML = `
        <div class="assignment-item">
          <h4>Error</h4>
          <p>Could not load top performer highlights.</p>
        </div>
      `;
    }
  } catch (error) {
    console.error(error);
  }
}

async function loadPerformanceStats() {
  try {
    const response = await fetch("backend/api/performance_stats.php");
    const result = await response.json();

    if (result.success) {
      document.getElementById("topPerformersCount").textContent = result.data.top_performers;
      document.getElementById("taskCompletionCount").textContent = result.data.task_completion + "%";
      document.getElementById("punctualityRateCount").textContent = result.data.punctuality_rate + "%";
      document.getElementById("avgRatingCount").textContent = result.data.avg_rating + " / 5";
    }
  } catch (error) {
    console.error(error);
  }
}

if (performanceForm) {
  performanceForm.addEventListener("submit", async function (e) {
    e.preventDefault();

    const formData = new FormData(performanceForm);

    try {
      const response = await fetch("backend/api/performance_create.php", {
        method: "POST",
        body: formData
      });

      const result = await response.json();

      if (result.success) {
        alert(result.message);
        performanceForm.reset();
        loadPerformanceRecords();
        loadPerformanceStats();
        loadDepartmentPerformance();
        loadTopPerformerHighlights();
      } else {
        alert(result.message);
      }
    } catch (error) {
      console.error(error);
      alert("Server error while saving performance review.");
    }
  });
}

document.getElementById("employeeName").addEventListener("change", autoFillPerformanceDepartment);

loadPerformanceStaffDropdown();
loadPerformanceRecords();
loadPerformanceStats();
loadDepartmentPerformance();
loadTopPerformerHighlights();
</script>
</body>
</html>