<?php
require_once 'backend/helpers/role_check.php';
require_once 'backend/helpers/sidebar.php';
requireRole(['Admin', 'Manager', 'Receptionist', 'Housekeeping']);
$currentPage = 'rooms.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Room Management</title>
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

    <!-- Main -->
    <main class="dashboard-main">

      <!-- Header -->
      <header class="dashboard-header">
        <div>
          <h1>Room Management</h1>
          <p>Manage room types, pricing, facilities, availability, maintenance, and cleaning status.</p>
        </div>

        <div class="header-actions">
          <div class="search-box">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" placeholder="Search rooms..." />
          </div>

          <button type="button" class="hero-btn primary" id="openRoomModalBtn">+ Add New Room</button>
        </div>
      </header>

      <!-- Top Summary Cards -->
      <section class="stats-grid room-top-stats">
        <div class="stat-card">
          <div class="stat-icon blue"><i class="fa-solid fa-hotel"></i></div>
          <div>
            <h3>Total Rooms</h3>
            <p id="totalRoomsCount">0</p>
          </div>
        </div>

        <div class="stat-card">
          <div class="stat-icon green"><i class="fa-solid fa-circle-check"></i></div>
          <div>
            <h3>Available</h3>
            <p id="availableRoomsCount">0</p>
          </div>
        </div>

        <div class="stat-card">
          <div class="stat-icon orange"><i class="fa-solid fa-broom"></i></div>
          <div>
            <h3>Cleaning Pending</h3>
            <p id="cleaningPendingCount">0</p>
          </div>
        </div>

        <div class="stat-card">
          <div class="stat-icon red"><i class="fa-solid fa-screwdriver-wrench"></i></div>
          <div>
            <h3>Maintenance</h3>
            <p id="maintenanceRoomsCount">0</p>
          </div>
        </div>
      </section>

      <!-- Filters -->
      <section class="panel room-filter-panel">
        <div class="panel-header">
          <h3>Room Filters</h3>
        </div>

        <div class="room-filters">
          <select>
            <option>All Room Types</option>
            <option>Standard Room</option>
            <option>Deluxe Room</option>
            <option>Luxury Suite</option>
            <option>Family Room</option>
          </select>

          <select>
            <option>All Status</option>
            <option>Available</option>
            <option>Occupied</option>
            <option>Reserved</option>
            <option>Maintenance</option>
          </select>

          <select>
            <option>Cleaning Status</option>
            <option>Clean</option>
            <option>Dirty</option>
            <option>Under Cleaning</option>
          </select>

          <button class="filter-btn">Apply Filter</button>
        </div>
      </section>

      <!-- Room Cards -->
      <section class="rooms-grid">

        <div class="room-pro-card">
          <img src="https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=1200&q=80" alt="Deluxe Room">
          <div class="room-card-body">
            <div class="room-card-top">
              <div>
                <h3>Deluxe Room</h3>
                <p>Room No: 101</p>
              </div>
              <span class="status confirmed">Available</span>
            </div>

            <p class="room-price">$120 <span>/ night</span></p>

            <div class="room-features">
              <span><i class="fa-solid fa-wifi"></i> Free WiFi</span>
              <span><i class="fa-solid fa-snowflake"></i> AC</span>
              <span><i class="fa-solid fa-tv"></i> Smart TV</span>
              <span><i class="fa-solid fa-bath"></i> Attached Bath</span>
            </div>

            <div class="room-status-row">
              <span class="small-badge clean">Clean</span>
              <span class="small-badge available">Ready to Book</span>
            </div>

            <div class="room-actions">
              <button class="room-btn primary-btn-small">Book Now</button>
              <button class="room-btn secondary-btn-small">View Details</button>
            </div>
          </div>
        </div>

        <div class="room-pro-card">
          <img src="https://images.unsplash.com/photo-1566665797739-1674de7a421a?auto=format&fit=crop&w=1200&q=80" alt="Luxury Suite">
          <div class="room-card-body">
            <div class="room-card-top">
              <div>
                <h3>Luxury Suite</h3>
                <p>Room No: 205</p>
              </div>
              <span class="status checked">Available</span>
            </div>

            <p class="room-price">$250 <span>/ night</span></p>

            <div class="room-features">
              <span><i class="fa-solid fa-wifi"></i> Free WiFi</span>
              <span><i class="fa-solid fa-bed"></i> King Bed</span>
              <span><i class="fa-solid fa-mug-hot"></i> Mini Bar</span>
              <span><i class="fa-solid fa-water-ladder"></i> Balcony View</span>
            </div>

            <div class="room-status-row">
              <span class="small-badge clean">Clean</span>
              <span class="small-badge occupied">Guest Checked In</span>
            </div>

            <div class="room-actions">
              <button class="room-btn primary-btn-small">Manage</button>
              <button class="room-btn secondary-btn-small">View Details</button>
            </div>
          </div>
        </div>

        <div class="room-pro-card">
          <img src="https://images.unsplash.com/photo-1631049307264-da0ec9d70304?auto=format&fit=crop&w=1200&q=80" alt="Standard Room">
          <div class="room-card-body">
            <div class="room-card-top">
              <div>
                <h3>Standard Room</h3>
                <p>Room No: 118</p>
              </div>
              <span class="status pending">Available</span>
            </div>

            <p class="room-price">$80 <span>/ night</span></p>

            <div class="room-features">
              <span><i class="fa-solid fa-wifi"></i> Free WiFi</span>
              <span><i class="fa-solid fa-fan"></i> Fan</span>
              <span><i class="fa-solid fa-tv"></i> TV</span>
              <span><i class="fa-solid fa-shower"></i> Shower</span>
            </div>

            <div class="room-status-row">
              <span class="small-badge dirty">Clean</span>
              <span class="small-badge reserved">Available</span>
            </div>

            <div class="room-actions">
              <button class="room-btn primary-btn-small">Prepare Room</button>
              <button class="room-btn secondary-btn-small">View Details</button>
            </div>
          </div>
        </div>

        <div class="room-pro-card">
          <img src="https://images.unsplash.com/photo-1590490360182-c33d57733427?auto=format&fit=crop&w=1200&q=80" alt="Family Room">
          <div class="room-card-body">
            <div class="room-card-top">
              <div>
                <h3>Family Room</h3>
                <p>Room No: 302</p>
              </div>
              <span class="status maintenance-status">Available</span>
            </div>

            <p class="room-price">$180 <span>/ night</span></p>

            <div class="room-features">
              <span><i class="fa-solid fa-wifi"></i> Free WiFi</span>
              <span><i class="fa-solid fa-couch"></i> Lounge Area</span>
              <span><i class="fa-solid fa-children"></i> Family Space</span>
              <span><i class="fa-solid fa-bath"></i> Large Bath</span>
            </div>

            <div class="room-status-row">
              <span class="small-badge maintenance">Clean</span>
              <span class="small-badge maintenance">Available</span>
            </div>

            <div class="room-actions">
              <button class="room-btn primary-btn-small">Update Status</button>
              <button class="room-btn secondary-btn-small">View Details</button>
            </div>
          </div>
        </div>
        

      </section>
      <div class="panel-header" style="margin-top: 30px;">
        <h3>New Added Rooms</h3>
      </div>
      <section class="rooms-grid" id="dynamicRoomsGrid"></section>

      <!-- Room Table -->
      <section class="panel">
        <div class="panel-header">
          <h3>Room Status Table</h3>
          <a href="#">Export</a>
        </div>

        <div class="table-responsive">
          <table class="dashboard-table">
            <thead>
              <tr>
                <th>Room No</th>
                <th>Type</th>
                <th>Price</th>
                <th>Availability</th>
                <th>Cleaning</th>
                <th>Maintenance</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody id="roomsTableBody"></tbody>
            
          </table>
        </div>
      </section>

    </main>
  </div>
  <div class="modal-overlay" id="roomModalOverlay">
  <div class="custom-modal">
    <div class="modal-header">
      <h3 id="roomModalTitle">Add New Room</h3>
      
      <button type="button" class="modal-close-btn" id="closeRoomModalBtn">&times;</button>
    </div>

    <form id="roomForm" class="room-form-modal">
      <input type="hidden" id="roomId" name="room_id">
      <div class="form-grid">
        <div class="form-group">
          <label for="roomNumber">Room Number</label>
          <input type="text" id="roomNumber" name="room_number" placeholder="Enter room number" required>
        </div>

        <div class="form-group">
          <label for="roomType">Room Type</label>
          <select id="roomType" name="room_type" required>
            <option value="">Select room type</option>
            <option value="Standard Room">Standard Room</option>
            <option value="Deluxe Room">Deluxe Room</option>
            <option value="Luxury Suite">Luxury Suite</option>
            <option value="Family Room">Family Room</option>
          </select>
        </div>

        <div class="form-group">
          <label for="roomPrice">Price Per Night</label>
          <input type="number" step="0.01" id="roomPrice" name="price_per_night" placeholder="Enter room price" required>
        </div>

        <div class="form-group">
          <label for="roomCapacity">Capacity</label>
          <input type="number" id="roomCapacity" name="capacity" placeholder="Enter guest capacity" required>
        </div>

        <div class="form-group">
          <label for="availabilityStatus">Availability Status</label>
          <select id="availabilityStatus" name="availability_status" required>
            <option value="Available">Available</option>
            <option value="Occupied">Occupied</option>
            <option value="Reserved">Reserved</option>
            <option value="Blocked">Blocked</option>
          </select>
        </div>

        <div class="form-group">
          <label for="cleaningStatus">Cleaning Status</label>
          <select id="cleaningStatus" name="cleaning_status" required>
            <option value="Clean">Clean</option>
            <option value="Dirty">Dirty</option>
            <option value="Under Cleaning">Under Cleaning</option>
            <option value="Unavailable">Unavailable</option>
          </select>
        </div>

        <div class="form-group">
          <label for="maintenanceStatus">Maintenance Status</label>
          <select id="maintenanceStatus" name="maintenance_status" required>
            <option value="No Issue">No Issue</option>
            <option value="Repair Needed">Repair Needed</option>
            <option value="Under Maintenance">Under Maintenance</option>
          </select>
        </div>

        <div class="form-group">
          <label for="floorNumber">Floor Number</label>
          <input type="number" id="floorNumber" name="floor_number" placeholder="Enter floor number">
        </div>
        <div class="form-group">
          <label for="roomImage">Room Image</label>
          <input type="file" id="roomImage" name="room_image" accept="image/*">
        </div>

        <div class="form-group full-width">
          <label for="roomDescription">Description</label>
          <textarea id="roomDescription" name="description" rows="4" placeholder="Enter room description"></textarea>
        </div>
      </div>

      <div class="booking-form-actions">
        <button type="button" class="secondary-btn-small room-btn" id="cancelRoomModalBtn">Cancel</button>
        <button type="submit" class="primary-btn-small room-btn">Save Room</button>
      </div>
    </form>
  </div>
</div>

  
<script src="script.js"></script>
<script>
const roomModalOverlay = document.getElementById("roomModalOverlay");
const openRoomModalBtn = document.getElementById("openRoomModalBtn");
const closeRoomModalBtn = document.getElementById("closeRoomModalBtn");
const cancelRoomModalBtn = document.getElementById("cancelRoomModalBtn");
const roomForm = document.getElementById("roomForm");

function openRoomModal() {
  roomModalOverlay.classList.add("show");
}

function closeRoomModal() {
  roomModalOverlay.classList.remove("show");
  roomForm.reset();
  document.getElementById("roomId").value = "";
  document.getElementById("roomModalTitle").textContent = "Add New Room";
}

if (openRoomModalBtn) {
  openRoomModalBtn.addEventListener("click", openRoomModal);
}

if (closeRoomModalBtn) {
  closeRoomModalBtn.addEventListener("click", closeRoomModal);
}

if (cancelRoomModalBtn) {
  cancelRoomModalBtn.addEventListener("click", closeRoomModal);
}

if (roomModalOverlay) {
  roomModalOverlay.addEventListener("click", function (e) {
    if (e.target === roomModalOverlay) {
      closeRoomModal();
    }
  });
}

document.addEventListener("click", function(e) {
  if (e.target.classList.contains("edit-room-btn")) {
    const room = JSON.parse(e.target.getAttribute("data-room"));

    document.getElementById("roomModalTitle").textContent = "Edit Room";
    document.getElementById("roomId").value = room.id;
    document.getElementById("roomNumber").value = room.room_number;
    document.getElementById("roomType").value = room.room_type;
    document.getElementById("roomPrice").value = room.price_per_night;
    document.getElementById("roomCapacity").value = room.capacity;
    document.getElementById("availabilityStatus").value = room.availability_status;
    document.getElementById("cleaningStatus").value = room.cleaning_status;
    document.getElementById("maintenanceStatus").value = room.maintenance_status;
    document.getElementById("floorNumber").value = room.floor_number || "";
    document.getElementById("roomDescription").value = room.description || "";

    roomModalOverlay.classList.add("show");
  }
});

async function loadRooms() {
  try {
    const response = await fetch("backend/api/get_rooms.php");
    const result = await response.json();

    if (result.success) {
      const tableBody = document.getElementById("roomsTableBody");
      tableBody.innerHTML = "";

      result.data.forEach((room) => {
        let availabilityClass = "confirmed";
        if (room.availability_status === "Occupied") availabilityClass = "checked";
        if (room.availability_status === "Reserved" || room.availability_status === "Blocked") availabilityClass = "pending";

        let cleaningClass = "clean";
        if (room.cleaning_status === "Dirty") cleaningClass = "dirty";
        if (room.cleaning_status === "Under Cleaning" || room.cleaning_status === "Unavailable") cleaningClass = "maintenance";

        let maintenanceClass = "available";
        if (room.maintenance_status === "Repair Needed" || room.maintenance_status === "Under Maintenance") {
          maintenanceClass = "maintenance";
        }

        let actionButton = `<span class="small-badge confirmed">Ready</span>`;
        if (room.cleaning_status === "Dirty") {
          actionButton = `<button type="button" class="primary-btn-small room-btn mark-clean-btn" data-room-id="${room.id}">Mark Clean</button>`;
        }

        const row = `
          <tr>
            <td>${room.room_number}</td>
            <td>${room.room_type}</td>
            <td>$${room.price_per_night}</td>
            <td><span class="status ${availabilityClass}">${room.availability_status}</span></td>
            <td><span class="small-badge ${cleaningClass}">${room.cleaning_status}</span></td>
            <td><span class="small-badge ${maintenanceClass}">${room.maintenance_status}</span></td>
            <td>
              <button type="button" class="primary-btn-small room-btn edit-room-btn" data-room='${JSON.stringify(room)}'>Edit</button>
              <button type="button" class="secondary-btn-small room-btn delete-room-btn" data-room-id="${room.id}">Delete</button>
              ${actionButton}
            </td>
          </tr>
        `;

        tableBody.innerHTML += row;
      });
    } else {
      alert(result.message);
    }
  } catch (error) {
    console.error(error);
    alert("Server error while loading rooms.");
  }
}

async function loadRoomStats() {
  try {
    const response = await fetch("backend/api/room_stats.php");
    const result = await response.json();

    if (result.success) {
      document.getElementById("totalRoomsCount").textContent = result.data.total_rooms;
      document.getElementById("availableRoomsCount").textContent = result.data.available_rooms;
      document.getElementById("cleaningPendingCount").textContent = result.data.cleaning_pending;
      document.getElementById("maintenanceRoomsCount").textContent = result.data.maintenance_rooms;
    }
  } catch (error) {
    console.error(error);
  }
}

if (roomForm) {
  roomForm.addEventListener("submit", async function (e) {
    e.preventDefault();

    const formData = new FormData(roomForm);

    try {
      const roomId = document.getElementById("roomId").value;
      const apiUrl = roomId
        ? "backend/api/room_update.php"
        : "backend/api/room_create.php";

      const response = await fetch(apiUrl, {

        method: "POST",
        body: formData
      });

      const result = await response.json();

      if (result.success) {
        alert(result.message);
        closeRoomModal();
        loadRooms();
        loadRoomStats();
        loadDynamicRoomCards();
      } else {
        alert(result.message);
      }
    } catch (error) {
      console.error(error);
      alert("Server error while saving room.");
    }
  });
}

document.addEventListener("click", async function (e) {
  if (e.target.classList.contains("mark-clean-btn")) {
    const roomId = e.target.getAttribute("data-room-id");

    if (!confirm("Mark this room as cleaned and available?")) {
      return;
    }

    const formData = new FormData();
    formData.append("room_id", roomId);

    try {
      const response = await fetch("backend/api/mark_room_clean.php", {
        method: "POST",
        body: formData
      });

      const result = await response.json();

      if (result.success) {
        alert(result.message);
        loadRooms();
        loadRoomStats();
      } else {
        alert(result.message);
      }
    } catch (error) {
      console.error(error);
      alert("Server error while updating room status.");
    }
  }
});

async function loadDynamicRoomCards() {
  try {
    const response = await fetch("backend/api/get_rooms.php");
    const result = await response.json();

    const grid = document.getElementById("dynamicRoomsGrid");
    if (!grid) return;

    grid.innerHTML = "";

    if (result.success && result.data.length > 0) {
      result.data.forEach((room) => {
        const image = room.image_path 
          ? room.image_path 
          : "https://via.placeholder.com/400x250?text=Room+Image";
        

        grid.innerHTML += `
          <div class="room-pro-card">
            <img src="${image}" alt="${room.room_type}">
            <div class="room-card-body">
              <div class="room-card-top">
                <div>
                  <h3>${room.room_type}</h3>
                  <p>Room No: ${room.room_number}</p>
                </div>
                <span class="status confirmed">${room.availability_status}</span>
              </div>

              <p class="room-price">$${parseFloat(room.price_per_night).toFixed(2)} <span>/ night</span></p>

              <div class="room-features">
                <span><i class="fa-solid fa-user-group"></i> ${room.capacity} Guests</span>
                <span><i class="fa-solid fa-layer-group"></i> Floor ${room.floor_number || "N/A"}</span>
                <span><i class="fa-solid fa-broom"></i> ${room.cleaning_status}</span>
                <span><i class="fa-solid fa-screwdriver-wrench"></i> ${room.maintenance_status}</span>
              </div>

              <p>${room.description || "Comfortable room with hotel facilities."}</p>

              <div class="room-actions">
                <button type="button" class="room-btn primary-btn-small edit-room-btn" data-room='${JSON.stringify(room)}'>Edit</button>
                <button type="button" class="room-btn secondary-btn-small delete-room-btn" data-room-id="${room.id}">Delete</button>
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

document.addEventListener("click", async function(e) {
  if (e.target.classList.contains("delete-room-btn")) {
    const roomId = e.target.getAttribute("data-room-id");

    if (!confirm("Are you sure you want to delete this room?")) {
      return;
    }

    const formData = new FormData();
    formData.append("room_id", roomId);

    try {
      const response = await fetch("backend/api/delete_room.php", {
        method: "POST",
        body: formData
      });

      const result = await response.json();
      alert(result.message);

      if (result.success) {
        loadRooms();
        loadRoomStats();
        loadDynamicRoomCards();
      }
    } catch (error) {
      console.error(error);
      alert("Server error while deleting room.");
    }
  }
});
loadRooms();
loadRoomStats();
loadDynamicRoomCards();
</script>
</body>
</html>