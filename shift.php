<?php
require_once 'backend/helpers/role_check.php';
require_once 'backend/helpers/sidebar.php';
requireRole(['Admin', 'Manager']);
$currentPage = 'shift.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Hotel Management System - Shift Scheduling</title>
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
          <h1>Shift Scheduling</h1>
          <p>Plan, assign, and monitor staff shifts across departments for smooth daily hotel operations.</p>
        </div>

        <div class="header-actions">
          <div class="search-box">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" placeholder="Search staff, shift, or department..." />
          </div>
          <button type="button" class="hero-btn primary">+ Create Shift</button>
        </div>
      </header>

      <!-- Top Stats -->
      <section class="stats-grid shift-stats">
        <div class="stat-card">
          <div class="stat-icon blue"><i class="fa-solid fa-business-time"></i></div>
          <div>
            <h3>Total Shifts Today</h3>
            <p id="totalShiftsTodayCount">0</p>
          </div>
        </div>

        <div class="stat-card">
          <div class="stat-icon green"><i class="fa-solid fa-user-check"></i></div>
          <div>
            <h3>Assigned Staff</h3>
            <p id="assignedStaffCount">0</p>
          </div>
        </div>

        <div class="stat-card">
          <div class="stat-icon orange"><i class="fa-solid fa-user-clock"></i></div>
          <div>
            <h3>Upcoming Shifts</h3>
            <p id="upcomingShiftsCount">0</p>
          </div>
        </div>

        <div class="stat-card">
          <div class="stat-icon purple"><i class="fa-solid fa-people-roof"></i></div>
          <div>
            <h3>Departments Covered</h3>
            <p id="departmentsCoveredCount">0</p>
          </div>
        </div>
      </section>

      <!-- Filters -->
      <section class="panel shift-filter-panel">
        <div class="panel-header">
          <h3>Shift Filters</h3>
        </div>

        <div class="shift-filters">
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
            <option>All Shift Types</option>
            <option>Morning Shift</option>
            <option>Evening Shift</option>
            <option>Night Shift</option>
            <option>Full Day</option>
          </select>

          <select>
            <option>All Status</option>
            <option>Scheduled</option>
            <option>In Progress</option>
            <option>Completed</option>
            <option>Absent</option>
          </select>

          <button type="button" class="filter-btn">Apply Filter</button>
        </div>
      </section>

      <section class="dashboard-content-grid shift-grid">

        <!-- Left -->
        <div class="dashboard-left">

          <div class="panel">
            <div class="panel-header">
              <h3>Create / Update Shift</h3>
              <a href="#">Save Draft</a>
            </div>

            <form class="shift-form"  id="shiftForm">
              <div class="form-grid">
                <div class="form-group">
                  <label for="shiftStaffName">Staff Name</label>
                  <select id="shiftStaffName" name="staff_name" required>
                    <option value="">Select staff</option>
                  </select>
                </div>

                <div class="form-group">
                  <label for="shiftDepartment">Department</label>
                  <select id="shiftDepartment" name="department" required>
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
                  <label for="shiftRole">Role</label>
                  <select id="shiftRole" name="role" required>
                    <option value="">Select role</option>
                    <option>Manager</option>
                    <option>Receptionist</option>
                    <option>Housekeeping Staff</option>
                    <option>Restaurant Staff</option>
                    <option>Activity Staff</option>
                    <option>Accountant</option>
                  </select>
                </div>

                <div class="form-group">
                  <label for="shiftDate">Shift Date</label>
                  <input type="date" id="shiftDate" name="shift_date" required />
                </div>

                <div class="form-group">
                  <label for="shiftType">Shift Type</label>
                  <select id="shiftType" name="shift_type" required>
                    <option value="">Select shift type</option>
                    <option>Morning Shift</option>
                    <option>Evening Shift</option>
                    <option>Night Shift</option>
                    <option>Full Day</option>
                  </select>
                </div>

                <div class="form-group">
                  <label for="shiftStatus">Shift Status</label>
                  <select id="shiftStatus" name="shift_status" required>
                    <option value="">Select status</option>
                    <option>Scheduled</option>
                    <option>In Progress</option>
                    <option>Completed</option>
                    <option>Absent</option>
                  </select>
                </div>

                <div class="form-group">
                  <label for="shiftStart">Start Time</label>
                  <input type="time" id="shiftStart" name="start_time" required />
                </div>

                <div class="form-group">
                  <label for="shiftEnd">End Time</label>
                  <input type="time" id="shiftEnd" name="end_time" required />
                </div>

                <div class="form-group full-width">
                  <label for="shiftNotes">Shift Notes</label>
                  <textarea id="shiftNotes" name="shift_notes"  rows="4" placeholder="Add shift instructions, responsibilities, overtime note, or replacement details"></textarea>
                </div>
              </div>

              <div class="booking-checkbox-row">
                <label><input type="checkbox" name="staff_notified" /> Staff Notified</label>
                <label><input type="checkbox" name="supervisor_approved"/> Supervisor Approved</label>
                <label><input type="checkbox" name="backup_staff_assigned"  /> Backup Staff Assigned</label>
                <label><input type="checkbox" name="overtime_allowed"/> Overtime Allowed</label>
              </div>

              <div class="booking-form-actions">
                <button type="reset" class="secondary-btn-small room-btn">Reset</button>
                <button type="submit" class="primary-btn-small room-btn">Save Shift</button>
              </div>
            </form>
          </div>

          <div class="panel">
            <div class="panel-header">
              <h3>Shift Schedule Table</h3>
              <a href="#">View Weekly Plan</a>
            </div>

            <div class="table-responsive">
              <table class="dashboard-table">
                <thead>
                  <tr>
                    <th>Staff</th>
                    <th>Department</th>
                    <th>Shift Type</th>
                    <th>Time</th>
                    <th style="min-width: 140px;">Status</th>
                    <th>Date</th>
                  </tr>
                </thead>
                <tbody id="shiftTableBody"></tbody>
                
              </table>
            </div>
          </div>

        </div>

        <!-- Right -->
        <div class="dashboard-right">

          <div class="panel">
            <div class="panel-header">
              <h3>Shift Distribution</h3>
            </div>
            <div class="shift-distribution-list" id="shiftDistributionList"></div>

            
          </div>

          <div class="panel">
            <div class="panel-header">
              <h3>Department Shift Coverage</h3>
            </div>

            <div class="assignment-list">
              <div class="assignment-item">
                <h4>Front Desk</h4>
                <p>Morning and evening shifts fully assigned.</p>
              </div>
              <div class="assignment-item">
                <h4>Housekeeping</h4>
                <p>Extra morning coverage assigned for high room turnover.</p>
              </div>
              <div class="assignment-item">
                <h4>Restaurant</h4>
                <p>Lunch and dinner service shifts arranged properly.</p>
              </div>
              <div class="assignment-item">
                <h4>Activities</h4>
                <p>Zipline and swimming staff assigned based on guest demand.</p>
              </div>
            </div>
          </div>

          <div class="panel">
            <div class="panel-header">
              <h3>Quick Shift Actions</h3>
            </div>

            <div class="quick-links">
              <a href="#" class="quick-link">Assign Shift</a>
              <a href="#" class="quick-link">Update Status</a>
              <a href="#" class="quick-link">Replace Staff</a>
              <a href="#" class="quick-link">Weekly Schedule</a>
              <a href="#" class="quick-link">Overtime Log</a>
              <a href="#" class="quick-link">Attendance View</a>
            </div>
          </div>

          <div class="panel">
            <div class="panel-header">
              <h3>Shift Alerts</h3>
            </div>

            <div class="notification-list">
              <div class="notification-item">
                <i class="fa-solid fa-user-clock"></i>
                <div>
                  <h4>Upcoming Evening Shift</h4>
                  <p>Restaurant and front desk evening shifts begin soon.</p>
                </div>
              </div>

              <div class="notification-item">
                <i class="fa-solid fa-user-slash"></i>
                <div>
                  <h4>Absent Staff Alert</h4>
                  <p>One operations shift is marked absent and needs replacement.</p>
                </div>
              </div>

              <div class="notification-item">
                <i class="fa-solid fa-business-time"></i>
                <div>
                  <h4>Overtime Notice</h4>
                  <p>Housekeeping team may require overtime for today’s cleaning load.</p>
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
const shiftForm = document.getElementById("shiftForm");

async function loadStaffDropdown() {
  try {
    const response = await fetch("backend/api/get_staff_dropdown.php");
    const result = await response.json();

    const staffSelect = document.getElementById("shiftStaffName");
    if (!staffSelect) return;

    staffSelect.innerHTML = '<option value="">Select staff</option>';

    if (result.success) {
      result.data.forEach((staff) => {
        const option = document.createElement("option");
        option.value = staff.full_name;
        option.textContent = `${staff.full_name} (${staff.role})`;
        option.dataset.department = staff.department;
        option.dataset.role = staff.role;
        staffSelect.appendChild(option);
      });
    }
  } catch (error) {
    console.error(error);
  }
}

function autoFillStaffDetails() {
  const staffSelect = document.getElementById("shiftStaffName");
  const selectedOption = staffSelect.options[staffSelect.selectedIndex];

  if (!selectedOption || !selectedOption.value) return;

  document.getElementById("shiftDepartment").value = selectedOption.dataset.department || "";
  document.getElementById("shiftRole").value = selectedOption.dataset.role || "";
}

async function loadShiftTable() {
  try {
    const response = await fetch("backend/api/get_staff_shifts.php");
    const result = await response.json();

    const tableBody = document.getElementById("shiftTableBody");
    if (!tableBody) return;

    tableBody.innerHTML = "";

    if (result.success) {
      if (!result.data || result.data.length === 0) {
        tableBody.innerHTML = `
          <tr>
            <td colspan="6" style="text-align:center;">No shift records found.</td>
          </tr>
        `;
        return;
      }

      result.data.forEach((shift) => {
        let statusClass = "scheduled";
        if (shift.shift_status === "In Progress") statusClass = "progress";
        if (shift.shift_status === "Completed") statusClass = "completed";
        if (shift.shift_status === "Absent") statusClass = "absent";

        const row = `
          <tr>
            <td>${shift.staff_name}</td>
            <td>${shift.department}</td>
            <td>${shift.shift_type}</td>
            <td>${shift.start_time} - ${shift.end_time}</td>
            <td><span class="shift-badge ${statusClass}">${shift.shift_status}</span></td>
            <td>${shift.shift_date}</td>
          </tr>
        `;
        tableBody.innerHTML += row;
      });
    }
  } catch (error) {
    console.error(error);
  }
}

async function loadShiftDistribution() {
  try {
    const response = await fetch("backend/api/shift_distribution.php");
    const result = await response.json();

    const distributionList = document.getElementById("shiftDistributionList");
    if (!distributionList) return;

    distributionList.innerHTML = "";

    if (result.success) {
      if (!result.data || result.data.length === 0) {
        distributionList.innerHTML = `
          <div class="distribution-item">
            <span>No shift data</span>
            <strong>0 Staff</strong>
          </div>
        `;
        return;
      }

      result.data.forEach((item) => {
        const row = `
          <div class="distribution-item">
            <span>${item.shift_type}</span>
            <strong>${item.total_shifts} Staff</strong>
          </div>
        `;
        distributionList.innerHTML += row;
      });
    } else {
      distributionList.innerHTML = `
        <div class="distribution-item">
          <span>Error</span>
          <strong>0 Staff</strong>
        </div>
      `;
    }
  } catch (error) {
    console.error(error);
  }
}

async function loadShiftStats() {
  try {
    const response = await fetch("backend/api/shift_stats.php");
    const result = await response.json();

    if (result.success) {
      document.getElementById("totalShiftsTodayCount").textContent = result.data.today_shifts;
      document.getElementById("assignedStaffCount").textContent = result.data.assigned_staff;
      document.getElementById("upcomingShiftsCount").textContent = result.data.upcoming_shifts;
      document.getElementById("departmentsCoveredCount").textContent = result.data.departments_covered;
    }
  } catch (error) {
    console.error(error);
  }
}

if (shiftForm) {
  shiftForm.addEventListener("submit", async function (e) {
    e.preventDefault();

    const formData = new FormData(shiftForm);

    try {
      const response = await fetch("backend/api/shift_create.php", {
        method: "POST",
        body: formData
      });

      const result = await response.json();

      if (result.success) {
        alert(result.message);
        shiftForm.reset();
        loadShiftTable();
        loadShiftStats();
        loadShiftDistribution();
      } else {
        alert(result.message);
      }
    } catch (error) {
      console.error(error);
      alert("Server error while saving shift.");
    }
  });
}

document.getElementById("shiftStaffName").addEventListener("change", autoFillStaffDetails);

loadStaffDropdown();
loadShiftTable();
loadShiftStats();
loadShiftDistribution();
</script>
</body>
</html>