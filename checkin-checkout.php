<?php
require_once 'backend/helpers/role_check.php';
require_once 'backend/helpers/sidebar.php';
requireRole(['Admin', 'Manager', 'Receptionist']);
$currentPage = 'checkin-checkout.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Hotel Management System - Check-In / Check-Out</title>
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

      <header class="dashboard-header">
        <div>
          <h1>Check-In / Check-Out Management</h1>
          <p>Handle guest arrival, room assignment, activity passes, deposits, final settlement, and room status updates.</p>
        </div>

        <div class="header-actions">
          <div class="search-box">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" placeholder="Search guest or booking..." />
          </div>
        </div>
      </header>

      <!-- Top Stats -->
      <section class="stats-grid">
        <div class="stat-card">
          <div class="stat-icon purple"><i class="fa-solid fa-arrow-right-to-bracket"></i></div>
          <div>
            <h3>Today's Check-Ins</h3>
            <p id="todayCheckinsCount">0</p>
          </div>
        </div>

        <div class="stat-card">
          <div class="stat-icon red"><i class="fa-solid fa-arrow-right-from-bracket"></i></div>
          <div>
            <h3>Today's Check-Outs</h3>
            <p id="todayCheckoutsCount">0</p>
          </div>
        </div>

        <div class="stat-card">
          <div class="stat-icon gold"><i class="fa-solid fa-wallet"></i></div>
          <div>
            <h3>Deposits Collected</h3>
            <p id="depositsCollectedCount">$0</p>
          </div>
        </div>

        <div class="stat-card">
          <div class="stat-icon green"><i class="fa-solid fa-circle-check"></i></div>
          <div>
            <h3>Completed Settlements</h3>
            <p id="completedSettlementsCount">0</p>
          </div>
        </div>
      </section>

      <section class="dashboard-content-grid">

        <!-- Left -->
        <div class="dashboard-left">

          <div class="panel">
            <div class="panel-header">
              <h3>Guest Check-In Form</h3>
              <a href="#">Save Draft</a>
            </div>

            <form class="check-form" id="checkInForm">
              <div class="form-grid">
                <div class="form-group">
                  <label for="guestName">Guest Name</label>
                  <input type="text" id="guestName" name="guest_name" placeholder="Enter guest name" required>
                </div>

                <div class="form-group">
                  <label for="bookingRef">Booking Reference</label>
                  <input type="text" id="bookingRef" name="booking_id" placeholder="Enter booking reference" required>
                </div>

                <div class="form-group">
                  <label for="guestId">Guest Verification ID</label>
                  <input type="text" id="guestId" name="guest_verification_id" placeholder="Passport / NID / Driving License" required>
                </div>

                <div class="form-group">
                  <label for="roomAllocation">Room Allocation</label>
                  <select id="roomAllocation" name="assigned_room_id" required>
                    <option value="">Select room</option>
                    
                  </select>
                </div>

                <div class="form-group">
                  <label for="activityPass">Activity Pass</label>
                  <select id="activityPass" name="activity_pass">
                    <option value="">Loading activities...</option>
                  </select>
                </div>

                <div class="form-group">
                  <label for="deposit">Deposit Collection</label>
                  <input type="number" id="deposit" name="deposit_collected" placeholder="Enter deposit amount">
                </div>
              </div>

              <div class="booking-form-actions">
                <button type="reset" class="secondary-btn-small room-btn">Reset</button>
                <button type="submit" class="primary-btn-small room-btn">Complete Check-In</button>
              </div>
            </form>
          </div>

          <div class="panel">
            <div class="panel-header">
              <h3>Guest Check-Out Form</h3>
              <a href="#">View Billing</a>
            </div>

            <form class="checkout-form" id="checkOutForm">
              <div class="form-grid">
                <div class="form-group">
                  <label for="checkoutGuest">Guest Name</label>
                  <input type="text" id="checkoutGuest" name="guest_name" placeholder="Enter guest name" required>
                </div>

                <div class="form-group">
                  <label for="checkoutRoom">Room Number</label>
                  <input type="text" id="checkoutRoom" name="assigned_room_number" placeholder="Enter room number" required>
                </div>

                <div class="form-group">
                  <label for="pendingCharges">Pending Charges</label>
                  <input type="number" id="pendingCharges" name="pending_charges" placeholder="Enter pending charges">
                </div>

                <div class="form-group">
                  <label for="finalSettlement">Final Bill Settlement</label>
                  <select id="finalSettlement" name="final_settlement_method"  required>
                    <option value="">Select payment method</option>
                    <option>Cash</option>
                    <option>Card</option>
                    <option>Wallet</option>
                    <option>Bank Transfer</option>
                  </select>
                </div>

                <div class="form-group full-width">
                  <label for="checkoutNotes">Check-Out Notes</label>
                  <textarea id="checkoutNotes" name="checkout_notes" rows="4" placeholder="Add notes for final settlement, damages, or service remarks"></textarea>
                </div>
              </div>

              <div class="booking-checkbox-row">
                <label><input type="checkbox" name="final_bill_settled"> Final Bill Settled</label>
                <label><input type="checkbox" name="room_status_auto_updated"> Room Status Auto Updated</label>
                <label><input type="checkbox" name="no_pending_services"> No Pending Services</label>
              </div>

              <div class="panel checkout-bill-panel">
                <div class="panel-header">
                  <h3>Final Bill Summary</h3>
                </div>
                
                <div class="room-status-grid">
                  <div class="mini-card">
                    <h4>Invoice ID</h4>
                    <p id="checkoutInvoiceId">-</p>
                  </div>
                  
                  <div class="mini-card">
                    <h4>Room Charge</h4>
                    <p id="checkoutRoomCharge">$0</p>
                  </div>
                  
                  <div class="mini-card">
                    <h4>Activity Charge</h4>
                    <p id="checkoutActivityCharge">$0</p>
                  </div>
                  
                  <div class="mini-card">
                    <h4>Service Charge</h4>
                    <p id="checkoutServiceCharge">$0</p>
                  </div>
                  
                  <div class="mini-card">
                    <h4>Tax</h4>
                    <p id="checkoutTaxAmount">$0</p>
                  </div>
                  
                  <div class="mini-card">
                    <h4>Deposit</h4>
                    <p id="checkoutDepositAmount">$0</p>
                  </div>
                  
                  <div class="mini-card">
                    <h4>Discount</h4>
                    <p id="checkoutDiscountAmount">$0</p>
                  </div>
                  
                  <div class="mini-card">
                    <h4>Total Bill</h4>
                    <p id="checkoutTotalAmount">$0</p>
                  </div>
                </div>
                
                <div class="assignment-list" style="margin-top: 16px;">
                  <div class="assignment-item">
                    <h4>Invoice Status</h4>
                    <p id="checkoutInvoiceStatus">-</p>
                  </div>
                  <div class="assignment-item">
                    <h4>Billing Notes</h4>
                    <p id="checkoutBillingNotes">-</p>
                  </div>
                </div>
              </div>

              <div class="booking-form-actions">
                <button type="reset" class="secondary-btn-small room-btn">Reset</button>
                <button type="submit" class="primary-btn-small room-btn">Complete Check-Out</button>
              </div>
            </form>
          </div>

        </div>

        <!-- Right -->
        <div class="dashboard-right">

          <div class="panel">
            <div class="panel-header">
              <h3>Check-In Tasks</h3>
            </div>

            <div class="assignment-list">
              <div class="assignment-item">
                <h4>Guest Verification</h4>
                <p>Verify guest identity before room handover.</p>
              </div>
              <div class="assignment-item">
                <h4>Room Allocation</h4>
                <p>Assign room based on booking and availability.</p>
              </div>
              <div class="assignment-item">
                <h4>Activity Pass Issue</h4>
                <p>Issue zipline or swimming access if booked.</p>
              </div>
              <div class="assignment-item">
                <h4>Deposit Collection</h4>
                <p>Collect deposit during guest arrival.</p>
              </div>
            </div>
          </div>

          <div class="panel">
            <div class="panel-header">
              <h3>Check-Out Tasks</h3>
            </div>

            <div class="assignment-list">
              <div class="assignment-item">
                <h4>Final Bill Settlement</h4>
                <p>Collect all outstanding payment before checkout.</p>
              </div>
              <div class="assignment-item">
                <h4>Pending Charges Review</h4>
                <p>Review activity, restaurant, and service charges.</p>
              </div>
              <div class="assignment-item">
                <h4>Update Room Status</h4>
                <p>Automatically mark room as vacant/dirty for housekeeping.</p>
              </div>
            </div>
          </div>

          <div class="panel">
            <div class="panel-header">
              <h3>Today’s Front Desk Log</h3>
            </div>

            <div class="table-responsive">
              <table class="dashboard-table">
                <thead>
                  <tr>
                    <th>Guest</th>
                    <th>Room</th>
                    <th>Action</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody id="frontdeskLogTableBody"></tbody>
                
              </table>
            </div>
          </div>

        </div>

      </section>

    </main>
  </div>


<script src="script.js"></script>
<script>
const checkForm = document.getElementById("checkInForm");
const checkoutForm = document.getElementById("checkOutForm");
const roomAllocation = document.getElementById("roomAllocation");
const bookingRefInput = document.getElementById("bookingRef");

const activityPass = document.getElementById("activityPass");

async function loadActivityPasses() {
  if (!activityPass) return;

  try {
    activityPass.innerHTML = `<option value="">Loading activities...</option>`;

    const response = await fetch("backend/api/get_activity_items.php");
    const result = await response.json();

    activityPass.innerHTML = `<option value="">Select activity pass</option>`;

    if (result.success && result.data && result.data.length > 0) {
      result.data.forEach((activity) => {
        const option = document.createElement("option");

        option.value = activity.item_name || activity.activity_name || activity.name;
        option.textContent = activity.item_name || activity.activity_name || activity.name;

        activityPass.appendChild(option);
      });
    } else {
      activityPass.innerHTML = `<option value="">No activities available</option>`;
    }
  } catch (error) {
    console.error(error);
    activityPass.innerHTML = `<option value="">Failed to load activities</option>`;
  }
}

function resetRoomAllocation(message = "Select room") {
  if (!roomAllocation) return;
  roomAllocation.innerHTML = `<option value="">${message}</option>`;
}

async function loadAvailableRoomsForBooking(bookingId) {
  if (!bookingId) {
    resetRoomAllocation("Select room");
    return;
  }

  try {
    resetRoomAllocation("Loading rooms...");

    const response = await fetch(`backend/api/get_available_rooms.php?booking_id=${encodeURIComponent(bookingId)}`);
    const result = await response.json();

    resetRoomAllocation("Select room");

    if (result.success) {
      if (!result.data || result.data.length === 0) {
        const option = document.createElement("option");
        option.value = "";
        option.textContent = "No matching clean room available";
        option.disabled = true;
        roomAllocation.appendChild(option);
        return;
      }

      result.data.forEach((room) => {
        const option = document.createElement("option");
        option.value = room.id;
        option.textContent = `${room.room_type} ${room.room_number}`;
        roomAllocation.appendChild(option);
      });
    } else {
      alert(result.message);
      resetRoomAllocation("Select room");
    }
  } catch (error) {
    console.error(error);
    resetRoomAllocation("Select room");
    alert("Server error while loading room options.");
  }
}
if (bookingRefInput) {
  bookingRefInput.addEventListener("change", function () {
    loadAvailableRoomsForBooking(this.value.trim());
  });

  bookingRefInput.addEventListener("blur", function () {
    loadAvailableRoomsForBooking(this.value.trim());
  });
}
async function loadFrontdeskLogs() {
  try {
    const response = await fetch("backend/api/get_frontdesk_logs.php");
    const result = await response.json();

    if (result.success) {
      const tableBody = document.getElementById("frontdeskLogTableBody");
      tableBody.innerHTML = "";

      result.data.forEach((log) => {
        let statusClass = "confirmed";
        if (log.action_status === "Pending Bill") statusClass = "pending";
        if (log.action_status === "Room Assigned") statusClass = "checked";

        const row = `
          <tr>
            <td>${log.guest_name}</td>
            <td>${log.room_number ?? '-'}</td>
            <td>${log.action_type}</td>
            <td><span class="status ${statusClass}">${log.action_status}</span></td>
          </tr>
        `;
        tableBody.innerHTML += row;
      });
    }
  } catch (error) {
    console.error(error);
  }
}

async function loadCheckinCheckoutStats() {
  try {
    const response = await fetch("backend/api/checkin_checkout_stats.php");
    const result = await response.json();

    if (result.success) {
      document.getElementById("todayCheckinsCount").textContent = result.data.today_checkins;
      document.getElementById("todayCheckoutsCount").textContent = result.data.today_checkouts;
      document.getElementById("depositsCollectedCount").textContent = `$${result.data.deposits_collected}`;
      document.getElementById("completedSettlementsCount").textContent = result.data.completed_settlements;
    }
  } catch (error) {
    console.error(error);
  }
}

if (checkForm) {
  checkForm.addEventListener("submit", async function (e) {
    e.preventDefault();

    const formData = new FormData(checkForm);

    try {
      const response = await fetch("backend/api/checkin_create.php", {
        method: "POST",
        body: formData
      });

      const result = await response.json();

      if (result.success) {
        alert(result.message);
        checkForm.reset();
        resetRoomAllocation("Select room");
        loadFrontdeskLogs();
        loadCheckinCheckoutStats();
      } else {
        alert(result.message);
      }
    } catch (error) {
      console.error(error);
      alert("Server error while completing check-in.");
    }
  });
}

if (checkoutForm) {
  checkoutForm.addEventListener("submit", async function (e) {
    e.preventDefault();

    const formData = new FormData(checkoutForm);

    try {
      const response = await fetch("backend/api/checkout_create.php", {
        method: "POST",
        body: formData
      });

      const result = await response.json();

      if (result.success) {
        alert(result.message);
        checkoutForm.reset();
        resetCheckoutBillSummary("-");
        resetRoomAllocation("Select room");
        loadFrontdeskLogs();
        loadCheckinCheckoutStats();
      } else {
        alert(result.message);
      }
    } catch (error) {
      console.error(error);
      alert("Server error while completing check-out.");
    }
  });
}

if (checkoutForm) {
  checkoutForm.addEventListener("reset", function () {
    setTimeout(() => {
      resetCheckoutBillSummary("-");
    }, 0);
  });
}

function resetCheckoutBillSummary(message = "-") {
  document.getElementById("checkoutInvoiceId").textContent = "-";
  document.getElementById("checkoutRoomCharge").textContent = "$0";
  document.getElementById("checkoutActivityCharge").textContent = "$0";
  document.getElementById("checkoutServiceCharge").textContent = "$0";
  document.getElementById("checkoutTaxAmount").textContent = "$0";
  document.getElementById("checkoutDepositAmount").textContent = "$0";
  document.getElementById("checkoutDiscountAmount").textContent = "$0";
  document.getElementById("checkoutTotalAmount").textContent = "$0";
  document.getElementById("checkoutInvoiceStatus").textContent = "-";
  document.getElementById("checkoutBillingNotes").textContent = message;
}

async function loadCheckoutBillDetails() {

  
  

  const guestNameInput = document.getElementById("checkoutGuest");
  
  const roomNumberInput = document.getElementById("checkoutRoom");

  if (!guestNameInput || !roomNumberInput) return;

  const guestName = guestNameInput.value.trim();
  const roomNumber = roomNumberInput.value.trim();

  if (guestName === "" || roomNumber === "") {
    resetCheckoutBillSummary("-");
    return;
  }

  try {
    const response = await fetch(
      `backend/api/get_checkout_bill.php?guest_name=${encodeURIComponent(guestName)}&room_number=${encodeURIComponent(roomNumber)}`
    );
    const result = await response.json();

    if (result.success) {
      document.getElementById("checkoutInvoiceId").textContent = result.data.invoice_id || "-";
      document.getElementById("checkoutRoomCharge").textContent = "$" + parseFloat(result.data.room_charge || 0).toFixed(2);
      document.getElementById("checkoutActivityCharge").textContent = "$" + parseFloat(result.data.activity_charge || 0).toFixed(2);
      document.getElementById("checkoutServiceCharge").textContent = "$" + parseFloat(result.data.service_charge || 0).toFixed(2);
      document.getElementById("checkoutTaxAmount").textContent = "$" + parseFloat(result.data.tax_amount || 0).toFixed(2);
      document.getElementById("checkoutDepositAmount").textContent = "$" + parseFloat(result.data.deposit_amount || 0).toFixed(2);
      document.getElementById("checkoutDiscountAmount").textContent = "$" + parseFloat(result.data.discount_amount || 0).toFixed(2);
      document.getElementById("checkoutTotalAmount").textContent = "$" + parseFloat(result.data.total_amount || 0).toFixed(2);
      document.getElementById("checkoutInvoiceStatus").textContent = result.data.invoice_status || "-";
      document.getElementById("checkoutBillingNotes").textContent = result.data.billing_notes || "-";
    } else {
      resetCheckoutBillSummary(result.message || "-");
    }
  } catch (error) {
    console.error(error);
  }
}

const checkoutGuestInput = document.getElementById("checkoutGuest");
const checkoutRoomInput = document.getElementById("checkoutRoom");

if (checkoutGuestInput) {
  checkoutGuestInput.addEventListener("change", loadCheckoutBillDetails);
  checkoutGuestInput.addEventListener("blur", loadCheckoutBillDetails);
}

if (checkoutRoomInput) {
  checkoutRoomInput.addEventListener("change", loadCheckoutBillDetails);
  checkoutRoomInput.addEventListener("blur", loadCheckoutBillDetails);
}

resetRoomAllocation("Select room");
loadActivityPasses();
loadFrontdeskLogs();
loadCheckinCheckoutStats();
loadCheckoutBillDetails();
</script>
</body>
</html>