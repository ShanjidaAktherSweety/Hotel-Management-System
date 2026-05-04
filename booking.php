<?php
require_once 'backend/helpers/role_check.php';
require_once 'backend/helpers/sidebar.php';
requireRole(['Admin', 'Manager', 'Receptionist']);
$currentPage = 'booking.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Booking & Reservation Management</title>
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
          <h1>Booking & Reservation Management</h1>
          <p>Manage online, offline, walk-in, group, and multi-room hotel reservations professionally.</p>
        </div>

        <div class="header-actions">
          <div class="search-box">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" placeholder="Search booking..." />
          </div>
          <button type="button" class="hero-btn primary">+ New Reservation</button>
        </div>
      </header>

      <!-- Top Cards -->
      <section class="stats-grid booking-stats">
        <div class="stat-card">
          <div class="stat-icon blue"><i class="fa-solid fa-calendar-days"></i></div>
          <div>
            <h3>Total Reservations</h3>
            <p id="totalReservationsCount">0</p>
          </div>
        </div>

        <div class="stat-card">
          <div class="stat-icon green"><i class="fa-solid fa-circle-check"></i></div>
          <div>
            <h3>Confirmed</h3>
            <p id="confirmedReservationsCount">0</p>
          </div>
        </div>

        <div class="stat-card">
          <div class="stat-icon orange"><i class="fa-solid fa-hourglass-half"></i></div>
          <div>
            <h3>Pending</h3>
            <p id="pendingReservationsCount">0</p>
          </div>
        </div>

        <div class="stat-card">
          <div class="stat-icon purple"><i class="fa-solid fa-users"></i></div>
          <div>
            <h3>Group Bookings</h3>
            <p id="groupBookingsCount">0</p>
          </div>
        </div>
      </section>

      <!-- Booking Form -->
      <section class="panel booking-form-panel">
        <div class="panel-header">
          <h3>Create New Reservation</h3>
          <a href="#">Save Draft</a>
        </div>

        <form class="booking-pro-form" id="bookingForm">
          <div class="form-grid">

            <div class="form-group">
              <label for="guestName">Guest Name</label>
              <input type="text" id="guestName" name="full_name" placeholder="Enter guest full name" required>
            </div>

            <div class="form-group">
              <label for="guestEmail">Email Address</label>
              <input type="email" id="guestEmail" name="email" placeholder="Enter email address" required>
            </div>

            <div class="form-group">
              <label for="guestPhone">Phone Number</label>
              <input type="tel" id="guestPhone" name="phone" placeholder="Enter phone number" required>
            </div>

            <div class="form-group">
              <label for="bookingType">Booking Type</label>
              <select id="bookingType" name="booking_type" required>
                <option value="">Select booking type</option>
                <option>Online Reservation</option>
                <option>Offline Reservation</option>
                <option>Walk-in Booking</option>
                <option>Corporate Booking</option>
                <option>Group Booking</option>
              </select>
            </div>

            <div class="form-group">
              <label for="checkIn">Check-In Date</label>
              <input type="date" id="checkIn" name="check_in_date" required>
            </div>

            <div class="form-group">
              <label for="checkOut">Check-Out Date</label>
              <input type="date" id="checkOut" name="check_out_date" required>
            </div>

            <div class="form-group">
              <label for="roomType">Room Type</label>
              <select id="roomType" name="room_type" required>
                <option value="">Select room type</option>
                <option>Standard Room</option>
                <option>Deluxe Room</option>
                <option>Luxury Suite</option>
                <option>Family Room</option>
              </select>
            </div>

            <div class="form-group">
              <label for="roomCount">Number of Rooms</label>
              <input type="number" id="roomCount" name="room_count" min="1" value="1" required>
            </div>

            <div class="form-group">
              <label for="adults">Adults</label>
              <input type="number" id="adults" name="adults" min="1" value="1" required>
            </div>

            <div class="form-group">
              <label for="children">Children</label>
              <input type="number" id="children" name="children" min="0" value="0">
            </div>

            <div class="form-group">
              <label for="paymentMethod">Payment Method</label>
              <select id="paymentMethod" name="payment_method" required>
                <option value="">Select payment method</option>
                <option>Cash</option>
                <option>Card</option>
                <option>Bank Transfer</option>
                <option>Mobile Wallet</option>
              </select>
            </div>

            <div class="form-group">
              <label for="deposit">Advance Deposit</label>
              <input type="number" id="deposit" name="deposit" placeholder="Enter advance amount">
            </div>

            <div class="form-group full-width">
              <label for="specialRequest">Special Request</label>
              <textarea id="specialRequest" name="special_request" rows="4" placeholder="Enter special requests or guest notes"></textarea>
            </div>

          </div>

          <div class="booking-checkbox-row">
            <label><input type="checkbox" name="airport_pickup"> Airport Pickup Required</label>
            <label><input type="checkbox" name="breakfast_included"> Breakfast Included</label>
            <label><input type="checkbox" name="extra_bed"> Add Extra Bed</label>
            <label><input type="checkbox" name="flexible_cancellation"> Flexible Cancellation</label>
          </div>

          <div class="booking-form-actions">
            <button type="reset" class="secondary-btn-small room-btn">Reset</button>
            <button type="submit" class="primary-btn-small room-btn">Confirm Booking</button>
          </div>
        </form>
      </section>

      <!-- Recent Reservation Table -->
      <section class="panel">
        <div class="panel-header">
          <h3>Recent Reservations</h3>
          <a href="#">View All</a>
        </div>

        <div class="table-responsive">
          <table class="dashboard-table">
            <thead>
              <tr>
                <th>Booking ID</th>
                <th>Guest Name</th>
                <th>Room Type</th>
                <th>Assigned Room</th>
                <th>Check-In</th>
                <th>Check-Out</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody id="bookingTableBody"></tbody>
            
          </table>
        </div>
      </section>

    </main>
  </div>
  <div class="modal-overlay" id="assignRoomModalOverlay">
  <div class="custom-modal">
    <div class="modal-header">
      <h3>Assign Room to Booking</h3>
      <button type="button" class="modal-close-btn" id="closeAssignRoomModalBtn">&times;</button>
    </div>

    <form id="assignRoomForm" class="room-form-modal">
      <input type="hidden" id="assignBookingId" name="booking_id">

      <div class="form-grid">
        <div class="form-group full-width">
          <label for="assignRoomSelect">Select Available Room</label>
          <select id="assignRoomSelect" name="room_id" required>
            <option value="">Select room</option>
          </select>
        </div>
      </div>

      <div class="booking-form-actions">
        <button type="button" class="secondary-btn-small room-btn" id="cancelAssignRoomBtn">Cancel</button>
        <button type="submit" class="primary-btn-small room-btn">Assign Room</button>
      </div>
    </form>
  </div>
</div>


<script src="script.js"></script>
<script>

const assignRoomModalOverlay = document.getElementById("assignRoomModalOverlay");
const closeAssignRoomModalBtn = document.getElementById("closeAssignRoomModalBtn");
const cancelAssignRoomBtn = document.getElementById("cancelAssignRoomBtn");
const assignRoomForm = document.getElementById("assignRoomForm");
const assignBookingId = document.getElementById("assignBookingId");
const assignRoomSelect = document.getElementById("assignRoomSelect");


const bookingForm = document.getElementById("bookingForm");
async function loadAvailableRoomsForAssign(bookingId) {
  try {
    assignRoomSelect.innerHTML = '<option value="">Loading rooms...</option>';

    const response = await fetch(`backend/api/get_available_rooms.php?booking_id=${encodeURIComponent(bookingId)}`);
    const result = await response.json();

    assignRoomSelect.innerHTML = '<option value="">Select room</option>';

    if (result.success) {
      if (!result.data || result.data.length === 0) {
        const option = document.createElement("option");
        option.value = "";
        option.textContent = "No matching clean room available";
        option.disabled = true;
        assignRoomSelect.appendChild(option);
        return;
      }

      result.data.forEach((room) => {
        const option = document.createElement("option");
        option.value = room.id;
        option.textContent = `${room.room_type} ${room.room_number}`;
        assignRoomSelect.appendChild(option);
      });
    } else {
      alert(result.message);
    }
  } catch (error) {
    console.error(error);
    assignRoomSelect.innerHTML = '<option value="">Select room</option>';
    alert("Server error while loading room options.");
  }
}


function openAssignRoomModal(bookingId) {
  assignBookingId.value = bookingId;
  assignRoomModalOverlay.classList.add("show");
  loadAvailableRoomsForAssign(bookingId);
}

function closeAssignRoomModal() {
  assignRoomModalOverlay.classList.remove("show");
  assignRoomForm.reset();
}
document.addEventListener("click", function (e) {
  if (e.target.classList.contains("assign-room-btn")) {
    const bookingId = e.target.getAttribute("data-booking-id");
    openAssignRoomModal(bookingId);
  }
});

if (closeAssignRoomModalBtn) {
  closeAssignRoomModalBtn.addEventListener("click", closeAssignRoomModal);
}

if (cancelAssignRoomBtn) {
  cancelAssignRoomBtn.addEventListener("click", closeAssignRoomModal);
}

if (assignRoomModalOverlay) {
  assignRoomModalOverlay.addEventListener("click", function (e) {
    if (e.target === assignRoomModalOverlay) {
      closeAssignRoomModal();
    }
  });
}

if (assignRoomForm) {
  assignRoomForm.addEventListener("submit", async function (e) {
    e.preventDefault();

    const formData = new FormData(assignRoomForm);

    try {
      const response = await fetch("backend/api/assign_room_to_booking.php", {
        method: "POST",
        body: formData
      });

      const result = await response.json();

      if (result.success) {
        alert(result.message);
        closeAssignRoomModal();
        loadBookings();
      } else {
        alert(result.message);
      }
    } catch (error) {
      console.error(error);
      alert("Server error while assigning room.");
    }
  });
}

async function loadBookings() {
  try {
    const response = await fetch("backend/api/get_staff_bookings.php");
    const result = await response.json();

    if (result.success) {
      const tableBody = document.getElementById("bookingTableBody");
      tableBody.innerHTML = "";

      result.data.forEach((booking) => {

        // STATUS CLASS
        let statusClass = "pending";
        if (booking.booking_status === "Confirmed") statusClass = "confirmed";
        if (booking.booking_status === "Checked In") statusClass = "checked";
        if (booking.booking_status === "Checked Out") statusClass = "checked";
        if (booking.booking_status === "Cancelled") statusClass = "cancelled";

        // ASSIGNED ROOM
        const assignedRoom = booking.assigned_room_number
          ? booking.assigned_room_number
          : "-";

        // ACTION BUTTON
        let actionButton = `<button type="button"
          class="primary-btn-small room-btn assign-room-btn"
          data-booking-id="${booking.id}">
          Assign Room
          </button>`;
        if (booking.assigned_room_number) {
          actionButton = `<span class="small-badge confirmed">Assigned</span>`;
        } else if (
          booking.booking_status === "Cancelled" ||
          booking.booking_status === "Checked In" ||
          booking.booking_status === "Checked Out"
        ) {
          actionButton = `<span class="small-badge pending">Not Allowed</span>`;
        }
 

        // FINAL ROW (IMPORTANT FIX)
        const row = `
          <tr>
            <td>#BK${booking.id}</td>
            <td>${booking.full_name}</td>
            <td>${booking.room_type}</td>
            <td>${assignedRoom}</td>
            <td>${booking.check_in_date}</td>
            <td>${booking.check_out_date}</td>
            <td><span class="status ${statusClass}">${booking.booking_status}</span></td>
            <td>${actionButton}</td>
          </tr>
        `;

        tableBody.innerHTML += row;
      });

    } else {
      alert(result.message);
    }
  } catch (error) {
    console.error(error);
    alert("Server error while loading bookings.");
  }
}

async function loadBookingStats() {
  try {
    const response = await fetch("backend/api/booking_stats.php");
    const result = await response.json();

    if (result.success) {
      document.getElementById("totalReservationsCount").textContent = result.data.total_reservations;
      document.getElementById("confirmedReservationsCount").textContent = result.data.confirmed;
      document.getElementById("pendingReservationsCount").textContent = result.data.pending;
      document.getElementById("groupBookingsCount").textContent = result.data.group_bookings;
    }
  } catch (error) {
    console.error(error);
  }
}

if (bookingForm) {
  bookingForm.addEventListener("submit", async function (e) {
    e.preventDefault();

    const formData = new FormData(bookingForm);

    try {
      const response = await fetch("backend/api/booking_create.php", {
        method: "POST",
        body: formData
      });

      const result = await response.json();

      if (result.success) {
        alert(result.message);
        bookingForm.reset();
        loadBookings();
        loadBookingStats();
      } else {
        alert(result.message);
      }
    } catch (error) {
      console.error(error);
      alert("Server error while saving booking.");
    }
  });
}

loadBookings();
loadBookingStats();
</script>

</body>
</html>