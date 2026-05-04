<?php
require_once 'backend/helpers/role_check.php';
require_once 'backend/helpers/sidebar.php';
requireRole(['Admin', 'Manager', 'Housekeeping']);
$currentPage = 'housekeeping.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Hotel Management System - Housekeeping</title>
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
          <h1>Housekeeping Management</h1>
          <p>Manage room assignments, cleaning updates, laundry tracking, and maintenance requests efficiently.</p>
        </div>

        <div class="header-actions">
          <div class="search-box">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" placeholder="Search room or housekeeping staff..." />
          </div>
          <button type="button" class="hero-btn primary">+ Assign Task</button>
        </div>
      </header>

      <!-- Top Stats -->
      <section class="stats-grid housekeeping-stats">
        <div class="stat-card">
          <div class="stat-icon blue"><i class="fa-solid fa-broom"></i></div>
          <div>
            <h3>Cleaning Tasks</h3>
            <p id="cleaningTasksCount">0</p>

          </div>
        </div>

        <div class="stat-card">
          <div class="stat-icon green"><i class="fa-solid fa-circle-check"></i></div>
          <div>
            <h3>Completed Today</h3>
            <p id="completedTodayCount">0</p>

          </div>
        </div>

        <div class="stat-card">
          <div class="stat-icon orange"><i class="fa-solid fa-shirt"></i></div>
          <div>
            <h3>Laundry Items</h3>
            <p id="laundryItemsCount">0</p>

          </div>
        </div>

        <div class="stat-card">
          <div class="stat-icon red"><i class="fa-solid fa-screwdriver-wrench"></i></div>
          <div>
            <h3>Maintenance Requests</h3>
            <p id="maintenanceRequestsCount">0</p>
          </div>
        </div>
      </section>

      <section class="dashboard-content-grid housekeeping-grid">

        <!-- Left -->
        <div class="dashboard-left">

          <div class="panel">
            <div class="panel-header">
              <h3>Room Cleaning Assignment</h3>
              <a href="#">Save Draft</a>
            </div>

            <form class="housekeeping-form" id="housekeepingForm">
              <div class="form-grid">
                <div class="form-group">
                  <label for="hkRoom">Room Number</label>
                  <select id="hkRoom" name="room_id" required>
                    <option value="">Select room</option>
                    
                  </select>
                </div>

                <div class="form-group">
                  <label for="hkStaff">Assign Staff</label>
                  <select id="hkStaff" name="assigned_staff" required>
                    <option value="">Select staff</option>
                    <option>Rahim</option>
                    <option>Karim</option>
                    <option>Nadia</option>
                    <option>Salma</option>
                  </select>
                </div>

                <div class="form-group">
                  <label for="hkStatus">Cleaning Status</label>
                  <select id="hkStatus" name="cleaning_status" required>
                    <option value="">Select status</option>
                    <option>Clean</option>
                    <option>Dirty</option>
                    <option>Under Cleaning</option>
                    <option>Ready for Inspection</option>
                  </select>
                </div>

                <div class="form-group">
                  <label for="hkPriority">Priority</label>
                  <select id="hkPriority" name="priority_level" required>
                    <option value="">Select priority</option>
                    <option>Low</option>
                    <option>Medium</option>
                    <option>High</option>
                    <option>Urgent</option>
                  </select>
                </div>

                <div class="form-group">
                  <label for="hkLaundry">Laundry Items</label>
                  <input type="number" id="hkLaundry" name="laundry_items" placeholder="Enter laundry quantity" />
                </div>

                <div class="form-group">
                  <label for="hkMaintenance">Maintenance Needed</label>
                  <select id="hkMaintenance" name="maintenance_needed">
                    <option value="">Select option</option>
                    <option>No</option>
                    <option>Electrical Issue</option>
                    <option>Plumbing Issue</option>
                    <option>Furniture Repair</option>
                    <option>General Maintenance</option>
                  </select>
                </div>

                <div class="form-group full-width">
                  <label for="hkNotes">Housekeeping Notes</label>
                  <textarea id="hkNotes" name="housekeeping_notes" rows="4" placeholder="Add cleaning notes, guest requests, or maintenance remarks"></textarea>
                </div>
              </div>

              <div class="booking-checkbox-row">
                <label><input type="checkbox" name="laundry_collected" /> Laundry Collected</label>
                <label><input type="checkbox" name="maintenance_reported"/> Maintenance Reported</label>
                <label><input type="checkbox" name="room_ready_for_guest"/> Room Ready for Guest</label>
                <label><input type="checkbox" name="supervisor_reviewed"/> Supervisor Reviewed</label>
              </div>

              <div class="booking-form-actions">
                <button type="reset" class="secondary-btn-small room-btn">Reset</button>
                <button type="submit" class="primary-btn-small room-btn">Update Task</button>
              </div>
            </form>
          </div>

          <div class="panel">
            <div class="panel-header">
              <h3>Room Status Overview</h3>
              <a href="#">View All</a>
            </div>

            <div class="table-responsive">
              <table class="dashboard-table">
                <thead>
                  <tr>
                    <th>Room</th>
                    <th>Assigned Staff</th>
                    <th>Cleaning</th>
                    <th>Laundry</th>
                    <th>Maintenance</th>
                  </tr>
                </thead>

                <tbody id="housekeepingTableBody"></tbody>
                
              </table>
            </div>
          </div>

        </div>

        <!-- Right -->
        <div class="dashboard-right">

          <div class="panel">
            <div class="panel-header">
              <h3>Assigned Staff</h3>
            </div>
            <div class="assignment-list" id="assignedStaffList"></div>

            
          </div>

          <div class="panel">
            <div class="panel-header">
              <h3>Laundry Tracking</h3>
            </div>
            <div class="equipment-list" id="laundryTrackingList"></div>

            
          </div>

          <div class="panel">
            <div class="panel-header">
              <h3>Maintenance Requests</h3>
            </div>
            <div class="notification-list" id="maintenanceRequestList"></div>

            
          </div>

          <div class="panel">
            <div class="panel-header">
              <h3>Quick Housekeeping Actions</h3>
            </div>

            <div class="quick-links">
              <a href="#" class="quick-link">Mark Clean</a>
              <a href="#" class="quick-link">Assign Staff</a>
              <a href="#" class="quick-link">Update Laundry</a>
              <a href="#" class="quick-link">Create Maintenance</a>
              <a href="#" class="quick-link">Inspection Log</a>
              <a href="#" class="quick-link">Floor Overview</a>
            </div>
          </div>

        </div>
      </section>

    </main>
  </div>

<script src="script.js"></script>
<script>
const housekeepingForm = document.getElementById("housekeepingForm");
const hkRoom = document.getElementById("hkRoom");

async function loadHousekeepingRooms() {
  try {
    const response = await fetch("backend/api/get_housekeeping_rooms.php");
    const result = await response.json();

    if (result.success && hkRoom) {
      hkRoom.innerHTML = '<option value="">Select room</option>';

      result.data.forEach((room) => {
        const option = document.createElement("option");
        option.value = room.id;
        option.textContent = `${room.room_number} - ${room.room_type}`;
        hkRoom.appendChild(option);
      });
    }
  } catch (error) {
    console.error(error);
  }
}

async function loadHousekeepingTasks() {
  try {
    const response = await fetch("backend/api/get_housekeeping_tasks.php");
    const result = await response.json();

    if (result.success) {
      const tableBody = document.getElementById("housekeepingTableBody");
      tableBody.innerHTML = "";

      result.data.forEach((task) => {
        let statusClass = "pending";
        if (task.cleaning_status === "Clean") statusClass = "confirmed";
        if (task.cleaning_status === "Under Cleaning") statusClass = "checked";
        if (task.cleaning_status === "Ready for Inspection") statusClass = "confirmed";

        const maintenanceText = task.maintenance_needed ? task.maintenance_needed : "No Issue";
        const maintenanceClass = task.maintenance_needed && task.maintenance_needed !== "No" ? "issue" : "ok";

        const laundryText = task.laundry_items > 0 ? `${task.laundry_items} Items` : "None";

        const row = `
          <tr>
            <td>${task.room_number}</td>
            <td>${task.assigned_staff}</td>
            <td><span class="status ${statusClass}">${task.cleaning_status}</span></td>
            <td>${laundryText}</td>
            <td><span class="hk-maint ${maintenanceClass}">${maintenanceText}</span></td>
          </tr>
        `;
        tableBody.innerHTML += row;
      });
    }
  } catch (error) {
    console.error(error);
  }
}

async function loadAssignedHousekeepingStaff() {
  try {
    const response = await fetch("backend/api/get_housekeeping_assigned_staff.php");
    const result = await response.json();

    const assignedStaffList = document.getElementById("assignedStaffList");
    if (!assignedStaffList) return;

    assignedStaffList.innerHTML = "";

    if (result.success && result.data.length > 0) {
      result.data.forEach((staff) => {
        const description = `${staff.total_tasks} task(s) assigned, ${staff.active_tasks} active, ${staff.completed_tasks} completed.`;

        const row = `
          <div class="assignment-item">
            <h4>${staff.assigned_staff}</h4>
            <p>${description}</p>
          </div>
        `;

        assignedStaffList.innerHTML += row;
      });
    } else {
      assignedStaffList.innerHTML = `
        <div class="assignment-item">
          <h4>No assigned staff yet</h4>
          <p>Housekeeping tasks have not been assigned yet.</p>
        </div>
      `;
    }
  } catch (error) {
    console.error(error);
  }
}

async function loadLaundryTracking() {
  try {
    const response = await fetch("backend/api/get_housekeeping_laundry_tracking.php");
    const result = await response.json();

    const laundryTrackingList = document.getElementById("laundryTrackingList");
    if (!laundryTrackingList) return;

    laundryTrackingList.innerHTML = "";

    if (result.success && result.data.length > 0) {
      result.data.forEach((item) => {
        laundryTrackingList.innerHTML += `
          <div class="equipment-item">
            <span>${item.label}</span>
            <strong>${item.value}</strong>
          </div>
        `;
      });
    } else {
      laundryTrackingList.innerHTML = `
        <div class="equipment-item">
          <span>No laundry data</span>
          <strong>0</strong>
        </div>
      `;
    }
  } catch (error) {
    console.error(error);
  }
}

async function loadMaintenanceRequests() {
  try {
    const response = await fetch("backend/api/get_housekeeping_maintenance_requests.php");
    const result = await response.json();

    const maintenanceRequestList = document.getElementById("maintenanceRequestList");
    if (!maintenanceRequestList) return;

    maintenanceRequestList.innerHTML = "";

    if (result.success && result.data.length > 0) {
      result.data.forEach((item) => {
        const notes = item.housekeeping_notes && item.housekeeping_notes.trim() !== ''
          ? item.housekeeping_notes
          : 'Maintenance issue reported from housekeeping task.';

        maintenanceRequestList.innerHTML += `
          <div class="notification-item">
            <i class="fa-solid fa-screwdriver-wrench"></i>
            <div>
              <h4>Room ${item.room_number} - ${item.maintenance_needed}</h4>
              <p>${notes}</p>
            </div>
          </div>
        `;
      });
    } else {
      maintenanceRequestList.innerHTML = `
        <div class="notification-item">
          <i class="fa-solid fa-circle-check"></i>
          <div>
            <h4>No maintenance requests</h4>
            <p>There are currently no active maintenance requests.</p>
          </div>
        </div>
      `;
    }
  } catch (error) {
    console.error(error);
  }
}


async function loadHousekeepingStats() {
  try {
    const response = await fetch("backend/api/housekeeping_stats.php");
    const result = await response.json();

    if (result.success) {
      document.getElementById("cleaningTasksCount").textContent = result.data.cleaning_tasks;
      document.getElementById("completedTodayCount").textContent = result.data.completed_today;
      document.getElementById("laundryItemsCount").textContent = result.data.laundry_items;
      document.getElementById("maintenanceRequestsCount").textContent = result.data.maintenance_requests;
    }
  } catch (error) {
    console.error(error);
  }
}

if (housekeepingForm) {
  housekeepingForm.addEventListener("submit", async function (e) {
    e.preventDefault();

    const formData = new FormData(housekeepingForm);

    try {
      const response = await fetch("backend/api/housekeeping_task_create.php", {
        method: "POST",
        body: formData
      });

      const result = await response.json();

      if (result.success) {
        alert(result.message);
        housekeepingForm.reset();
        loadHousekeepingTasks();
        loadHousekeepingStats();
        loadAssignedHousekeepingStaff();
        loadLaundryTracking();
        loadMaintenanceRequests();
      } else {
        alert(result.message);
      }
    } catch (error) {
      console.error(error);
      alert("Server error while saving housekeeping task.");
    }
  });
}

loadHousekeepingRooms();
loadHousekeepingTasks();
loadHousekeepingStats();
loadAssignedHousekeepingStaff();
loadLaundryTracking();
loadMaintenanceRequests();

</script>
</body>
</html>