<?php
require_once 'backend/helpers/role_check.php';
require_once 'backend/helpers/sidebar.php';
requireRole(['Admin', 'Manager']);
$currentPage = 'staff.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Hotel Management System - Staff Management</title>
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
          <h1>Staff Management</h1>
          <p>Manage employee roles, departments, contact details, active status, and internal team assignments.</p>
        </div>

        <div class="header-actions">
          <div class="search-box">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" placeholder="Search staff, role, or department..." />
          </div>
          <button type="button" class="hero-btn primary">+ Add Staff Member</button>
        </div>
      </header>

      <!-- Top Stats -->
      <section class="stats-grid staff-stats">
        <div class="stat-card">
          <div class="stat-icon blue"><i class="fa-solid fa-users"></i></div>
          <div>
            <h3>Total Staff</h3>
            <p id="totalStaffCount">0</p>
          </div>
        </div>

        <div class="stat-card">
          <div class="stat-icon green"><i class="fa-solid fa-user-check"></i></div>
          <div>
            <h3>Active Staff</h3>
            <p id="activeStaffCount">0</p>
          </div>
        </div>

        <div class="stat-card">
          <div class="stat-icon orange"><i class="fa-solid fa-building-user"></i></div>
          <div>
            <h3>Departments</h3>
            <p id="departmentsCount">0</p>
          </div>
        </div>

        <div class="stat-card">
          <div class="stat-icon purple"><i class="fa-solid fa-user-plus"></i></div>
          <div>
            <h3>New This Month</h3>
            <p id="newThisMonthCount">0</p>
          </div>
        </div>
      </section>

      <!-- Filters -->
      <section class="panel staff-filter-panel">
        <div class="panel-header">
          <h3>Staff Filters</h3>
        </div>

        <div class="staff-filters">
          <select id="filterRole">
            <option value="">All Roles</option>
            <option value="Admin">Admin</option>
            <option value="Manager">Manager</option>
            <option value="Receptionist">Receptionist</option>
            <option value="Accountant">Accountant</option>
            <option value="Housekeeping Staff">Housekeeping Staff</option>
            <option value="Activity Staff">Activity Staff</option>
            <option value="Restaurant Staff">Restaurant Staff</option>
          </select>
          
          
          <select id="filterDepartment">
            <option value="">All Departments</option>
            <option value="Front Desk">Front Desk</option>
            <option value="Finance">Finance</option>
            <option value="Housekeeping">Housekeeping</option>
            <option value="Activities">Activities</option>
            <option value="Restaurant">Restaurant</option>
            <option value="Operations">Operations</option>
          </select>
          
          <select id="filterStatus">
            <option value="">All Status</option>
            <option value="Active">Active</option>
            <option value="On Leave">On Leave</option>
            <option value="Inactive">Inactive</option>
          </select>
          
          <button type="button" class="filter-btn" id="applyStaffFilterBtn">Apply Filter</button>
        </div>
      </section>

      <section class="dashboard-content-grid staff-grid">

        <!-- Left -->
        <div class="dashboard-left">

          <div class="panel">
            <div class="panel-header">
              <h3>Add / Update Staff Member</h3>
              <a href="#">Save Draft</a>
            </div>

            <form class="staff-form" id="staffForm">
              <div class="form-grid">
                <div class="form-group">
                  <label for="staffName">Full Name</label>
                  <input type="text" id="staffName" name="full_name" placeholder="Enter full name" required />
                </div>

                <div class="form-group">
                  <label for="staffEmail">Email Address</label>
                  <input type="email" id="staffEmail" name="email" placeholder="Enter email address" required />
                </div>

                <div class="form-group">
                  <label for="staffPhone">Phone Number</label>
                  <input type="tel" id="staffPhone" name="phone"  placeholder="Enter phone number" required />
                </div>

                <div class="form-group">
                  <label for="staffRole">Role</label>
                  <select id="staffRole" name="role" required>
                    <option value="">Select role</option>
                    <option>Admin</option>
                    <option>Manager</option>
                    <option>Receptionist</option>
                    <option>Accountant</option>
                    <option>Housekeeping Staff</option>
                    <option>Activity Staff</option>
                    <option>Restaurant Staff</option>
                  </select>
                </div>

                <div class="form-group">
                  <label for="staffDepartment">Department</label>
                  <select id="staffDepartment" name="department" required>
                    <option value="">Select department</option>
                    <option>Front Desk</option>
                    <option>Finance</option>
                    <option>Housekeeping</option>
                    <option>Activities</option>
                    <option>Restaurant</option>
                    <option>Operations</option>
                  </select>
                </div>

                <div class="form-group">
                  <label for="staffStatus">Employment Status</label>
                  <select id="staffStatus" name="employment_status" required>
                    <option value="">Select status</option>
                    <option>Active</option>
                    <option>On Leave</option>
                    <option>Inactive</option>
                  </select>
                </div>

                <div class="form-group">
                  <label for="joiningDate">Joining Date</label>
                  <input type="date" id="joiningDate" name="joining_date"  required />
                </div>

                <div class="form-group">
                  <label for="accessLevel">Access Level</label>
                  <select id="accessLevel" name="access_level" required>
                    <option value="">Select access level</option>
                    <option>Full Access</option>
                    <option>Department Access</option>
                    <option>Limited Access</option>
                  </select>
                </div>

                <div class="form-group full-width">
                  <label for="staffNotes">Staff Notes</label>
                  <textarea id="staffNotes" name="staff_notes" rows="4" placeholder="Add employee notes, responsibilities, or internal remarks"></textarea>
                </div>
              </div>

              <div class="booking-checkbox-row">
                <label><input type="checkbox" name="login_account_active" /> Login Account Active</label>
                <label><input type="checkbox" name="role_assigned"/> Role Assigned</label>
                <label><input type="checkbox" name="department_confirmed"/> Department Confirmed</label>
                <label><input type="checkbox" name="notify_employee"/> Notify Employee</label>
              </div>

              <div class="booking-form-actions">
                <button type="reset" class="secondary-btn-small room-btn">Reset</button>
                <button type="submit" class="primary-btn-small room-btn">Save Staff Record</button>
              </div>
            </form>
          </div>

          <div class="panel">
            <div class="panel-header">
              <h3>Staff Directory</h3>
              <a href="#">View All</a>
            </div>

            <div class="table-responsive">
              <table class="dashboard-table">
                <thead>
                  <tr>
                    <th>Name</th>
                    <th>Role</th>
                    <th>Department</th>
                    <th>Status</th>
                    <th>Contact</th>
                    <th>Access</th>
                  </tr>
                </thead>
                <tbody id="staffTableBody"></tbody>
                
              </table>
            </div>
          </div>

        </div>

        <!-- Right -->
        <div class="dashboard-right">

          <div class="panel">
            <div class="panel-header">
              <h3>Department Distribution</h3>
            </div>

            <div class="staff-distribution-list" id="departmentDistributionList"></div>
              
          </div>

          <div class="panel">
            <div class="panel-header">
              <h3>Key Roles</h3>
            </div>

            <div class="assignment-list">
              <div class="assignment-item">
                <h4>Admin</h4>
                <p>Manages overall system access and administrative control.</p>
              </div>
              <div class="assignment-item">
                <h4>Manager</h4>
                <p>Supervises departments and hotel-wide operations.</p>
              </div>
              <div class="assignment-item">
                <h4>Receptionist</h4>
                <p>Handles bookings, check-in, and guest support.</p>
              </div>
              <div class="assignment-item">
                <h4>Housekeeping Staff</h4>
                <p>Maintains room cleanliness, laundry, and room readiness.</p>
              </div>
            </div>
          </div>

          <div class="panel">
            <div class="panel-header">
              <h3>Quick Staff Actions</h3>
            </div>

            <div class="quick-links">
              <a href="#" class="quick-link">Add Employee</a>
              <a href="#" class="quick-link">Assign Role</a>
              <a href="#" class="quick-link">Update Status</a>
              <a href="#" class="quick-link">View Directory</a>
              <a href="shift.html" class="quick-link">Shift Schedule</a>
              <a href="performance.html" class="quick-link">Performance</a>
            </div>
          </div>

          <div class="panel">
            <div class="panel-header">
              <h3>Staff Alerts</h3>
            </div>

            <div class="notification-list">
              <div class="notification-item">
                <i class="fa-solid fa-user-clock"></i>
                <div>
                  <h4>Leave Notice</h4>
                  <p>One housekeeping staff member is currently on leave.</p>
                </div>
              </div>

              <div class="notification-item">
                <i class="fa-solid fa-user-plus"></i>
                <div>
                  <h4>New Staff Joined</h4>
                  <p>Two new restaurant team members joined this month.</p>
                </div>
              </div>

              <div class="notification-item">
                <i class="fa-solid fa-shield-halved"></i>
                <div>
                  <h4>Access Review Needed</h4>
                  <p>One inactive account requires role and access verification.</p>
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
const staffForm = document.getElementById("staffForm");

async function loadStaffRecords(role = "", department = "", status = "") {
  try {
    const response = await fetch(
      `backend/api/filter_staff_records.php?role=${encodeURIComponent(role)}&department=${encodeURIComponent(department)}&status=${encodeURIComponent(status)}`
    );
    const result = await response.json();

    if (result.success) {
      const tableBody = document.getElementById("staffTableBody");
      tableBody.innerHTML = "";

      if (!result.data || result.data.length === 0) {
        tableBody.innerHTML = `
          <tr>
            <td colspan="6" style="text-align:center;">No staff records found.</td>
          </tr>
        `;
        return;
      }

      result.data.forEach((staff) => {
        let statusClass = "active-staff";
        if (staff.employment_status === "On Leave") statusClass = "leave-staff";
        if (staff.employment_status === "Inactive") statusClass = "inactive-staff";

        const row = `
          <tr>
            <td>${staff.full_name}</td>
            <td>${staff.role}</td>
            <td>${staff.department}</td>
            <td><span class="staff-badge ${statusClass}">${staff.employment_status}</span></td>
            <td>${staff.phone}</td>
            <td>${staff.access_level}</td>
          </tr>
        `;

        tableBody.innerHTML += row;
      });
    } else {
      alert(result.message);
    }
  } catch (error) {
    console.error(error);
    alert("Server error while loading staff records.");
  }
}

const applyStaffFilterBtn = document.getElementById("applyStaffFilterBtn");

if (applyStaffFilterBtn) {
  applyStaffFilterBtn.addEventListener("click", function () {
    const role = document.getElementById("filterRole").value;
    const department = document.getElementById("filterDepartment").value;
    const status = document.getElementById("filterStatus").value;

    loadStaffRecords(role, department, status);
  });
}

async function loadStaffStats() {
  try {
    const response = await fetch("backend/api/staff_stats.php");
    const result = await response.json();

    if (result.success) {
      document.getElementById("totalStaffCount").textContent = result.data.total_staff;
      document.getElementById("activeStaffCount").textContent = result.data.active_staff;
      document.getElementById("departmentsCount").textContent = result.data.departments;
      document.getElementById("newThisMonthCount").textContent = result.data.new_this_month;
    }
  } catch (error) {
    console.error(error);
  }
}

if (staffForm) {
  staffForm.addEventListener("submit", async function (e) {
    e.preventDefault();

    const formData = new FormData(staffForm);

    try {
      const response = await fetch("backend/api/staff_create.php", {
        method: "POST",
        body: formData
      });

      const result = await response.json();

      if (result.success) {
        alert(result.message);
        staffForm.reset();
        loadStaffRecords();
        loadStaffStats();
        loadDepartmentDistribution();
      } else {
        alert(result.message);
      }
    } catch (error) {
      console.error(error);
      alert("Server error while saving staff record.");
    }
  });
}

function bindDepartmentDistributionClicks() {
  const departmentItems = document.querySelectorAll(".staff-department-item");

  departmentItems.forEach((item) => {
    item.addEventListener("click", function () {
      const department = this.getAttribute("data-department") || "";

      const filterDepartment = document.getElementById("filterDepartment");
      const filterRole = document.getElementById("filterRole");
      const filterStatus = document.getElementById("filterStatus");

      if (filterDepartment) filterDepartment.value = department;
      if (filterRole) filterRole.value = "";
      if (filterStatus) filterStatus.value = "";

      loadStaffRecords("", department, "");
    });
  });
}

async function loadDepartmentDistribution() {
  try {
    const response = await fetch("backend/api/staff_department_distribution.php");
    const result = await response.json();

    const distributionList = document.getElementById("departmentDistributionList");
    if (!distributionList) return;

    distributionList.innerHTML = "";

    if (result.success) {
      if (!result.data || result.data.length === 0) {
        distributionList.innerHTML = `
          <div class="distribution-item">
            <span>No department data</span>
            <strong>0 Staff</strong>
          </div>
        `;
        return;
      }

      result.data.forEach((item) => {
        
      const row = `
        <div class="distribution-item staff-department-item" data-department="${item.department}">
        
          <span>${item.department}</span>
          <strong>${item.total_staff} Staff</strong>
        
        </div>
        
      `;
        distributionList.innerHTML += row;
      });
      bindDepartmentDistributionClicks();
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

loadStaffRecords();
loadStaffStats();
loadDepartmentDistribution();
</script>
</body>
</html>