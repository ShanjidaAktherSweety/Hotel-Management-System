<?php
require_once 'backend/helpers/role_check.php';
require_once 'backend/helpers/sidebar.php';
requireRole(['Admin', 'Manager', 'Activity Staff']);
$currentPage = 'activities.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Hotel Management System - Activities</title>
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
          <h1>Activity Management</h1>
          <p>Manage zipline and swimming bookings, schedules, equipment, staff assignment, and activity billing.</p>
        </div>

        <div class="header-actions">
          <div class="search-box">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" placeholder="Search activities..." />
          </div>
          <button type="button" class="hero-btn primary">+ New Activity Booking</button>
        </div>
      </header>

      <!-- Top Cards -->
      <section class="stats-grid activity-stats">
        <div class="stat-card">
          <div class="stat-icon teal"><i class="fa-solid fa-person-swimming"></i></div>
          <div>
            <h3>Total Activity Bookings</h3>
            <p id="totalActivityBookingsCount">0</p>
          </div>
        </div>

        <div class="stat-card">
          <div class="stat-icon purple"><i class="fa-solid fa-mountain"></i></div>
          <div>
            <h3>Zipline Sessions</h3>
            <p id="ziplineSessionsCount">0</p>
          </div>
        </div>

        <div class="stat-card">
          <div class="stat-icon blue"><i class="fa-solid fa-water-ladder"></i></div>
          <div>
            <h3>Swimming Sessions</h3>
            <p id="swimmingSessionsCount">0</p>
          </div>
        </div>

        <div class="stat-card">
          <div class="stat-icon gold"><i class="fa-solid fa-sack-dollar"></i></div>
          <div>
            <h3>Activity Revenue</h3>
            <p id="activityRevenue">$0</p>
          </div>
        </div>
      </section>

      <div class="panel">
        <div class="panel-header">
          <h3>Add New Activity</h3>
        </div>
        
        <form id="activityItemForm" enctype="multipart/form-data">
          <input type="hidden" name="activity_item_id" id="activityItemId">
          <div class="form-grid">
            <div class="form-group">
              <label>Activity Name</label>
              <input type="text" name="activity_name" required>
            </div>
            
            <div class="form-group">
              <label>Activity Type</label>
              <select name="activity_type" required>
                <option value="">Select type</option>
                <option>Zipline</option>
                <option>Swimming</option>
                <option>Adventure</option>
                <option>Outdoor</option>
              </select>
            </div>
            
            <div class="form-group">
              <label>Price</label>
              <input type="number" name="price" step="0.01" required>
            </div>
            
            <div class="form-group">
              <label>Status</label>
              <select name="activity_status" required>
                <option>Available</option>
                <option>Unavailable</option>
              </select>
            </div>
            
            <div class="form-group">
              <label>Time Slots</label>
              <input type="text" name="time_slots" placeholder="09:00 AM - 05:00 PM">
            </div>
            
            <div class="form-group">
              <label>Age Range</label>
              <input type="text" name="age_range" placeholder="12+ Years">
            </div>
            
            <div class="form-group">
              <label>Weight Limit</label>
              <input type="text" name="weight_limit" placeholder="40kg - 100kg">
            </div>
            
            <div class="form-group">
              <label>Image</label>
              <input type="file" name="activity_image" accept="image/*">
            </div>
            
            <div class="form-group full-width">
              <label>Description</label>
              <textarea name="description" rows="4" required></textarea>
            </div>
          </div>
          
          <div class="booking-form-actions">
            <button type="submit" class="primary-btn-small room-btn">Save Activity</button>
          </div>
        </form>
      </div>

      <!-- Activities Grid -->
      
      <div class="panel-header" style="margin-top: 30px;">
        <h3>New Added Activities</h3>
      </div>
      <section class="activities-grid" id="dynamicActivityGrid"></section>

      <!-- Main lower content -->
      <section class="dashboard-content-grid activity-content-grid">

        <!-- Left -->
        <div class="dashboard-left">

          <div class="panel">
            <div class="panel-header">
              <h3>Recent Activity Bookings</h3>
              <a href="#">View All</a>
            </div>

            <div class="table-responsive">
              <table class="dashboard-table">
                <thead>
                  <tr>
                    <th>Guest</th>
                    <th>Activity</th>
                    <th>Time Slot</th>
                    <th>Assigned Staff</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody id="activityBookingTableBody"></tbody>
                  
              </table>
            </div>
          </div>

          <div class="panel">
            <div class="panel-header">
              <h3>Activity Booking Form</h3>
              <a href="#">Save Draft</a>
            </div>

            <form class="activity-booking-form" id="activityBookingForm">
              <input type="hidden" id="activityBookingId" name="booking_id">
              <div class="form-grid">
                <div class="form-group">
                  <label for="activityCheckedInGuest">Checked-In Guest</label>
                  <select id="activityCheckedInGuest" required>
                    <option value="">Select checked-in guest</option>
                  </select>
                </div>
                
                <div class="form-group">
                  <label for="activityGuest">Guest Name</label>
                  <input type="text" id="activityGuest" name="guest_name" placeholder="Guest name" readonly required>
                </div>

                <div class="form-group">
                  <label for="activityType">Activity Type</label>
                  <select id="activityType" name="activity_type" required>
                    <option value="">Select activity</option>
                    
                  </select>
                </div>

                <div class="form-group">
                  <label for="activityDate">Booking Date</label>
                  <input type="date" id="activityDate" name="booking_date" required>
                </div>

                <div class="form-group">
                  <label for="activitySlot">Time Slot</label>
                  <select id="activitySlot" name="time_slot" required>
                    <option value="">Select time slot</option>
                    <option value="09:00 AM">09:00 AM</option>
                    <option value="10:00 AM">10:00 AM</option>
                    <option value="11:00 AM">11:00 AM</option>
                    <option value="02:00 AM">02:00 PM</option>
                    <option value="04:00 AM">04:00 PM</option>
                  </select>
                </div>

                <div class="form-group">
                  <label for="activityWeight">Guest Weight (kg)</label>
                  <input type="number" id="activityWeight" name="guest_weight" placeholder="Enter weight">
                </div>

                <div class="form-group">
                  <label for="activityAge">Guest Age</label>
                  <input type="number" id="activityAge" name="guest_age" placeholder="Enter age">
                </div>

                <div class="form-group">
                  <label for="activityStaff">Assign Staff</label>
                  <select id="activityStaff" name="assigned_staff" required>
                    <option value="">Select staff</option>
                  </select>
                  
                </div>

                <div class="form-group">
                  <label for="activityBilling">Billing Type</label>
                  <select id="activityBilling" name="billing_type" required>
                    <option value="">Select billing type</option>
                    <option value="Add to Room Bill">Add to Room Bill</option>
                    <option value="External Customer Invoice">External Customer Invoice</option>
                  </select>
                </div>

                <div class="form-group full-width">
                  <label for="activityNotes">Notes / Safety Instructions</label>
                  <textarea id="activityNotes" name="notes" rows="4" placeholder="Add guest notes, safety acknowledgement, or equipment notes"></textarea>
                </div>
              </div>

              <div class="booking-checkbox-row">
                <label><input type="checkbox" name="safety_acknowledged"> Safety Guidelines Acknowledged</label>
                <label><input type="checkbox" name="equipment_issued"> Equipment Issued</label>
                <label><input type="checkbox" name="external_customer"> External Customer</label>
                <label><input type="checkbox" name="room_bill_added"> Bill Added to Guest Room</label>
              </div>

              <div class="booking-form-actions">
                <button type="reset" class="secondary-btn-small room-btn">Reset</button>
                <button type="submit" class="primary-btn-small room-btn">Confirm Activity Booking</button>
              </div>
            </form>
          </div>
        </div>

        <!-- Right -->
        <div class="dashboard-right">

          <div class="panel">
            <div class="panel-header">
              <h3>Equipment Status</h3>
              <a href="#">Manage</a>
            </div>
            <div class="equipment-list" id="activityEquipmentList"></div>

            
          </div>

          <div class="panel">
            <div class="panel-header">
              <h3>Staff Assignment</h3>
            </div>

            <div class="assignment-list" id="activityStaffAssignmentList"></div>

          
          </div>

          <div class="panel">
            <div class="panel-header">
              <h3>Pool Maintenance</h3>
            </div>

            <div class="maintenance-box">
              <div class="maintenance-row">
                <span>Water Quality Check</span>
                <strong class="text-success">Completed</strong>
              </div>
              <div class="maintenance-row">
                <span>Pool Cleaning</span>
                <strong class="text-success">Completed</strong>
              </div>
              <div class="maintenance-row">
                <span>Equipment Check</span>
                <strong class="text-warning">Pending</strong>
              </div>
            </div>
          </div>

          <div class="panel">
            <div class="panel-header">
              <h3>Activity Billing Summary</h3>
            </div>

            <div class="revenue-box">
              <div class="revenue-item">
                <span>Zipline Charges</span>
                <strong>$250</strong>
              </div>
              <div class="revenue-item">
                <span>Swimming Charges</span>
                <strong>$150</strong>
              </div>
              <div class="revenue-item">
                <span>External Invoices</span>
                <strong>$50</strong>
              </div>
            </div>
          </div>
        </div>

      </section>
    </main>
  </div>

<script src="script.js"></script>
<script>
  const activityForm = document.getElementById("activityBookingForm");
  const activityCheckedInGuest = document.getElementById("activityCheckedInGuest");
  const activityBookingId = document.getElementById("activityBookingId");
  const activityGuest = document.getElementById("activityGuest");
  async function loadActivityCheckedInGuests() {
  try {
    const response = await fetch("backend/api/get_checkedin_guests.php");
    const result = await response.json();

    if (!activityCheckedInGuest) return;

    activityCheckedInGuest.innerHTML = '<option value="">Select checked-in guest</option>';

    if (result.success && result.data.length > 0) {
      result.data.forEach((guest) => {
        const option = document.createElement("option");

        option.value = guest.id;
        option.textContent = `${guest.full_name} - Room ${guest.assigned_room_number}`;

        option.setAttribute("data-name", guest.full_name);

        activityCheckedInGuest.appendChild(option);
      });
    }
  } catch (error) {
    console.error(error);
  }
}

if (activityCheckedInGuest) {
  activityCheckedInGuest.addEventListener("change", function () {
    const selectedOption = this.options[this.selectedIndex];

    if (this.value) {
      activityBookingId.value = this.value;
      activityGuest.value = selectedOption.getAttribute("data-name") || "";
    } else {
      activityBookingId.value = "";
      activityGuest.value = "";
    }
  });
}

  

  async function loadActivityBookings() {
    try {
      const response = await fetch("backend/api/get_activity_bookings.php");
      const result = await response.json();

      if (result.success) {
        const tableBody = document.getElementById("activityBookingTableBody");
        tableBody.innerHTML = "";

        result.data.forEach((booking) => {
          let statusClass = "pending";

          if (booking.status === "Confirmed") {
            statusClass = "confirmed";
          } else if (booking.status === "Completed") {
            statusClass = "checked";
          } else if (booking.status === "Cancelled") {
            statusClass = "cancelled";
          }

          const row = `
            <tr>
              <td>${booking.guest_name}</td>
              <td>${booking.activity_type}</td>
              <td>${booking.time_slot}</td>
              <td>${booking.assigned_staff}</td>
              <td><span class="status ${statusClass}">${booking.status}</span></td>
            </tr>
          `;

          tableBody.innerHTML += row;
        });
      } else {
        alert(result.message);
      }
    } catch (error) {
      console.error(error);
      alert("Server error while loading activity bookings.");
    }
  }

  async function loadActivityStats() {
    try {
      const response = await fetch("backend/api/activity_stats.php");
      const result = await response.json();

      if (result.success) {
        document.getElementById("totalActivityBookingsCount").textContent = result.data.total_activity_bookings;
        document.getElementById("ziplineSessionsCount").textContent = result.data.zipline_sessions;
        document.getElementById("swimmingSessionsCount").textContent = result.data.swimming_sessions;
      }
    } catch (error) {
      console.error(error);
    }
  }

  if (activityForm) {
    activityForm.addEventListener("submit", async function (e) {
      e.preventDefault();

      const formData = new FormData(activityForm);

      try {
        const response = await fetch("backend/api/activity_booking_create.php", {
          method: "POST",
          body: formData
        });

        const text = await response.text();
        console.log("Raw response:", text);

        let result;
        try {
          result = JSON.parse(text);
        } catch (jsonError) {
          alert("PHP error or invalid JSON response. Check console.");
          console.error("Invalid JSON:", text);
          return;
        }

        if (result.success) {
          alert(result.message);
          activityForm.reset();
          activityBookingId.value = "";
          if (activityCheckedInGuest) activityCheckedInGuest.value = "";
          loadActivityBookings();
          loadActivityStats();
          loadActivityStaffAssignments();
        } else {
          alert(result.message);
        }
      } catch (error) {
        console.error(error);
        alert("Server error while saving activity booking.");
      }
    });
  }

  async function loadActivityEquipment() {
  try {
    const response = await fetch("backend/api/get_activity_equipment.php");
    const result = await response.json();

    const equipmentList = document.getElementById("activityEquipmentList");
    if (!equipmentList) return;

    equipmentList.innerHTML = "";

    if (result.success && result.data.length > 0) {
      result.data.forEach((item) => {
        let label = "Available";

        if (parseInt(item.quantity) <= 0) {
          label = "Out of Stock";
        } else if (parseInt(item.quantity) <= parseInt(item.minimum_stock)) {
          label = "Low Stock";
        }

        equipmentList.innerHTML += `
          <div class="equipment-item">
            <span>${item.item_name}</span>
            <strong>${item.quantity} ${label}</strong>
          </div>
        `;
      });
    } else {
      equipmentList.innerHTML = `
        <div class="equipment-item">
          <span>No activity equipment found</span>
          <strong>0 Available</strong>
        </div>
      `;
    }
  } catch (error) {
    console.error(error);
  }
}
async function loadActivityStaffDropdown() {
  try {
    const response = await fetch("backend/api/get_activity_staff.php");
    const result = await response.json();

    const staffSelect = document.getElementById("activityStaff");
    if (!staffSelect) return;

    staffSelect.innerHTML = '<option value="">Select staff</option>';

    if (result.success && result.data.length > 0) {
      result.data.forEach((staff) => {
        staffSelect.innerHTML += `
          <option value="${staff.full_name}">
            ${staff.full_name} (${staff.role})
          </option>
        `;
      });
    }
  } catch (error) {
    console.error(error);
  }
}

async function loadActivityStaffAssignments() {
  try {
    const response = await fetch("backend/api/get_activity_staff_assignment.php");
    const result = await response.json();

    const assignmentList = document.getElementById("activityStaffAssignmentList");
    if (!assignmentList) return;

    assignmentList.innerHTML = "";

    if (result.success && result.data.length > 0) {
      result.data.forEach((item) => {
        assignmentList.innerHTML += `
          <div class="assignment-item">
            <h4>${item.assigned_staff}</h4>
            <p>${item.activities} - ${item.total_bookings} booking(s)</p>
          </div>
        `;
      });
    } else {
      assignmentList.innerHTML = `
        <div class="assignment-item">
          <h4>No staff assigned yet</h4>
          <p>No activity bookings have been assigned to staff.</p>
        </div>
      `;
    }
  } catch (error) {
    console.error(error);
  }
}
const activityItemForm = document.getElementById("activityItemForm");

if (activityItemForm) {
  activityItemForm.addEventListener("submit", async function(e) {
    e.preventDefault();

    const formData = new FormData(activityItemForm);
    const activityItemId = document.getElementById("activityItemId").value;

    const apiUrl = activityItemId
      ? "backend/api/update_activity_item.php"
      : "backend/api/add_activity_item.php";

    try {
      const response = await fetch(apiUrl, {
        method: "POST",
        body: formData
      });

      const result = await response.json();
      alert(result.message);

      if (result.success) {
        activityItemForm.reset();
        document.getElementById("activityItemId").value = "";
        loadActivityItems();
        loadActivityTypeDropdown();
      }
    } catch (error) {
      console.error(error);
      alert("Server error while saving activity item.");
    }
  });
}
async function loadActivityItems() {
  try {
    const response = await fetch("backend/api/get_activity_items.php");
    const result = await response.json();

    const grid = document.getElementById("dynamicActivityGrid");
    if (!grid) return;

    grid.innerHTML = "";

    if (result.success && result.data.length > 0) {
      result.data.forEach((item) => {
        const image = item.image_path ? item.image_path : "default-activity.jpg";

        grid.innerHTML += `
          <div class="activity-pro-card">
            <div class="activity-image">
              <img src="${image}" alt="${item.activity_name}">
              <span class="activity-tag">${item.activity_type}</span>
            </div>

            <div class="activity-card-body">
              <div class="activity-card-top">
                <div>
                  <h3>${item.activity_name}</h3>
                  <p>${item.description}</p>
                </div>
                <span class="status confirmed">${item.activity_status}</span>
              </div>

              <div class="activity-meta-grid">
                <div class="meta-box">
                  <span>Price</span>
                  <strong>$${parseFloat(item.price).toFixed(2)}</strong>
                </div>
                
                <div class="meta-box">
                  <span>Time Slots</span>
                  <strong>${item.time_slots || 'Flexible'}</strong>
                </div>
                <div class="meta-box">
                  <span>Age Range</span>
                  <strong>${item.age_range || 'All Ages'}</strong>
                </div>
                <div class="meta-box">
                  <span>Weight Limit</span>
                  <strong>${item.weight_limit || 'N/A'}</strong>
                </div>
              </div>
              <div class="activity-actions">
                <button type="button" class="room-btn primary-btn-small" onclick="editActivityItem(${item.id})">Edit</button>
                <button type="button" class="room-btn secondary-btn-small" onclick="deleteActivityItem(${item.id})">Delete</button>
              </div>
            </div>
          </div>
        `;
      });
    }
  } catch (error) {
    console.error(error);
  }
}

async function editActivityItem(id) {
  try {
    const response = await fetch("backend/api/get_activity_items.php");
    const result = await response.json();

    if (!result.success) {
      alert(result.message);
      return;
    }

    const item = result.data.find(activity => parseInt(activity.id) === parseInt(id));

    if (!item) {
      alert("Activity item not found.");
      return;
    }

    document.getElementById("activityItemId").value = item.id;
    document.querySelector('[name="activity_name"]').value = item.activity_name;
    document.querySelector('[name="activity_type"]').value = item.activity_type;
    document.querySelector('[name="price"]').value = item.price;
    document.querySelector('[name="description"]').value = item.description;
    document.querySelector('[name="time_slots"]').value = item.time_slots || "";
    document.querySelector('[name="age_range"]').value = item.age_range || "";
    document.querySelector('[name="weight_limit"]').value = item.weight_limit || "";
    document.querySelector('[name="activity_status"]').value = item.activity_status;

    window.scrollTo({ top: 0, behavior: "smooth" });
  } catch (error) {
    console.error(error);
    alert("Server error while loading activity item.");
  }
}

async function deleteActivityItem(id) {
  if (!confirm("Are you sure you want to delete this activity item?")) return;

  const formData = new FormData();
  formData.append("id", id);

  try {
    const response = await fetch("backend/api/delete_activity_item.php", {
      method: "POST",
      body: formData
    });

    const result = await response.json();
    alert(result.message);

    if (result.success) {
      loadActivityItems();
    }
  } catch (error) {
    console.error(error);
    alert("Server error while deleting activity item.");
  }
}

async function loadActivityTypeDropdown() {
  try {
    const response = await fetch("backend/api/get_activity_items.php");
    const result = await response.json();

    const activitySelect = document.getElementById("activityType");
    if (!activitySelect) return;

    activitySelect.innerHTML = '<option value="">Select activity</option>';

    
    if (result.success && result.data.length > 0) {
      result.data.forEach((item) => {
        if (item.activity_status === "Available") {
          activitySelect.innerHTML += `
            <option value="${item.activity_name}">
              ${item.activity_name}
            </option>
          `;
        }
      });
    }else {
      activitySelect.innerHTML = '<option value="">No activities available</option>';
    }
  } catch (error) {
    console.error(error);
  }
}

  loadActivityBookings();
  loadActivityStats();
  loadActivityEquipment();
  loadActivityStaffDropdown();
  loadActivityStaffAssignments();
  loadActivityItems();
  loadActivityTypeDropdown();
  loadActivityCheckedInGuests();
</script>
</body>
</html>