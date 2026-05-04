<?php
require_once 'backend/helpers/role_check.php';
require_once 'backend/helpers/sidebar.php';
requireRole(['Admin', 'Manager', 'Accountant']);
$currentPage = 'billing.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Hotel Management System - Billing</title>
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
          <h1>Payment & Billing Management</h1>
          <p>Manage invoices, deposits, room and activity charges, discounts, refunds, and payment settlements.</p>
        </div>

        <div class="header-actions">
          <div class="search-box">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" placeholder="Search invoice or guest..." />
          </div>
          <button type="button" class="hero-btn primary">+ Generate Invoice</button>
        </div>
      </header>

      <!-- Top Stats -->
      <section class="stats-grid billing-stats">
        <div class="stat-card">
          <div class="stat-icon gold"><i class="fa-solid fa-file-invoice"></i></div>
          <div>
            <h3>Total Invoices</h3>
            <p id="totalInvoicesCount">0</p>
          </div>
        </div>

        <div class="stat-card">
          <div class="stat-icon green"><i class="fa-solid fa-circle-check"></i></div>
          <div>
            <h3>Paid Bills</h3>
            <p id="paidBillsCount">0</p>
          </div>
        </div>

        <div class="stat-card">
          <div class="stat-icon orange"><i class="fa-solid fa-hourglass-half"></i></div>
          <div>
            <h3>Pending Payments</h3>
            <p id="pendingBillsCount">0</p>

          </div>
        </div>

        <div class="stat-card">
          <div class="stat-icon blue"><i class="fa-solid fa-wallet"></i></div>
          <div>
            <h3>Revenue Today</h3>
            <p id="revenueTodayCount">$0</p>
          </div>
        </div>
      </section>

      <!-- Invoice + Summary -->
      <section class="dashboard-content-grid billing-main-grid">

        <!-- Left -->
        <div class="dashboard-left">

          <div class="panel">
            <div class="panel-header">
              <h3>Create / Update Invoice</h3>
              <a href="#">Save Draft</a>
            </div>

            <form class="billing-form" id="billingForm">
              <input type="hidden" id="billingBookingId" name="booking_id">
              <input type="hidden" id="billingRoomNumber" name="room_number">
              <input type="hidden" id="billingCheckInDate" name="check_in_date">
              <input type="hidden" id="billingCheckOutDate" name="check_out_date">
              <input type="hidden" id="billingNightsStayed" name="nights_stayed">

              
              <div class="form-grid">
                <div class="form-group">
                  <label for="checkedInGuestSelect">Checked-In Guest</label>
                  <select id="checkedInGuestSelect">
                    <option value="">Select checked-in guest</option>
                  </select>
                </div>
                <div class="form-group">
                  <label for="invoiceGuest">Guest Name</label>
                  <input type="text" id="invoiceGuest" name="guest_name" placeholder="Enter guest name" required />
                </div>
                

                <div class="form-group">
                  <label for="invoiceId">Invoice ID</label>
                  <input type="text" id="invoiceId"  name="invoice_id" placeholder="INV-1001" required />
                </div>

                <div class="form-group">
                  <label for="invoiceCurrency">Currency</label>
                  <select id="invoiceCurrency" name="currency" required>
                    <option value="">Select currency</option>
                    <option>USD ($)</option>
                    <option>GBP (£)</option>
                    <option>EUR (€)</option>
                    <option>BDT (৳)</option>
                  </select>
                </div>

                <div class="form-group">
                  <label for="paymentMethodBill">Payment Method</label>
                  <select id="paymentMethodBill" name="payment_method" required>
                    <option value="">Select payment method</option>
                    <option>Card</option>
                    <option>Cash</option>
                    <option>Mobile Wallet</option>
                    <option>Bank Transfer</option>
                  </select>
                </div>

                <div class="form-group">
                  <label for="roomCharge">Room Charge</label>
                  <input type="number" id="roomCharge" name="room_charge" placeholder="Enter room charge" required />
                </div>

                <div class="form-group">
                  <label for="activityCharge">Activity Charge</label>
                  <input type="number" id="activityCharge" name="activity_charge" placeholder="Auto activity charge" readonly />
                </div>

                <div class="form-group">
                  <label for="serviceCharge">Service Charge</label>
                  <input type="number" id="serviceCharge" name="service_charge" placeholder="Auto restaurant/service charge" readonly />
                </div>

                <div class="form-group">
                  <label for="taxAmount">Tax / VAT</label>
                  <input type="number" id="taxAmount" name="tax_amount" placeholder="Enter tax amount" />
                </div>

                <div class="form-group">
                  <label for="depositAmount">Advance Deposit</label>
                  <input type="number" id="depositAmount" name="deposit_amount" placeholder="Enter deposit amount" />
                </div>

                <div class="form-group">
                  <label for="discountAmount">Discount / Coupon</label>
                  <input type="number" id="discountAmount" name="discount_amount" placeholder="Enter discount amount" />
                </div>

                <div class="form-group">
                  <label for="refundAmount">Refund Amount</label>
                  <input type="number" id="refundAmount" name="refund_amount" placeholder="Enter refund amount if needed" />
                </div>

                <div class="form-group">
                  <label for="invoiceStatus">Invoice Status</label>
                  <select id="invoiceStatus" name="invoice_status" required>
                    <option value="">Select status</option>
                    <option>Paid</option>
                    <option>Pending</option>
                    <option>Partially Paid</option>
                    <option>Refunded</option>
                  </select>
                </div>

                <div class="form-group full-width">
                  <label for="billingNotes">Billing Notes</label>
                  <textarea id="billingNotes" name="billing_notes" rows="4" placeholder="Add invoice notes, pre-authorization notes, refund notes, or customer billing information"></textarea>
                </div>
              </div>

              <div class="booking-checkbox-row">
                <label><input type="checkbox" name="add_to_room_bill" /> Add to Guest Room Bill</label>
                <label><input type="checkbox" name="external_customer_invoice" /> External Customer Invoice</label>
                <label><input type="checkbox" name="pre_authorization_required" /> Pre-Authorization Required</label>
                <label><input type="checkbox" name="auto_generate_final_bill" /> Auto Generate Final Bill</label>
              </div>

              <div class="booking-form-actions">
                <button type="reset" class="secondary-btn-small room-btn">Reset</button>
                <button type="submit" class="primary-btn-small room-btn">Save Invoice</button>
              </div>
            </form>
          </div>

          <div class="panel">
            <div class="panel-header">
              <h3>Recent Invoices</h3>
              <a href="#">View All</a>
            </div>

            <div class="table-responsive">
              <table class="dashboard-table">
                <thead>
                  <tr>
                    <th>Invoice ID</th>
                    <th>Guest</th>
                    <th>Total</th>
                    <th>Method</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody id="billingTableBody"></tbody>
                
              </table>
            </div>
          </div>

        </div>

        <!-- Right -->
        <div class="dashboard-right">

          <div class="panel">
            <div class="panel-header">
              <h3>Bill Summary</h3>
            </div>

            <div class="billing-summary-box">
              <div class="billing-summary-item">
                <span>Room Charges</span>
                <strong id="summaryRoomCharge">$0.00</strong>
                
              </div>
              <div class="billing-summary-item">
                <span>Activity Charges</span>
                <strong id="summaryActivityCharge">$0.00</strong>
              </div>
              <div class="billing-summary-item">
                <span>Restaurant / Service Charges</span>
                
                <strong id="summaryServiceCharge">$0.00</strong>
              </div>
              <div class="billing-summary-item">
                <span>Tax / VAT</span>
                <strong id="summaryTaxAmount">$0.00</strong>
              </div>

              <div class="billing-summary-item">
                <span>Deposit</span>
                <strong id="summaryDepositAmount">-$0.00</strong>
              </div>

              <div class="billing-summary-item">
                <span>Discounts</span>
                <strong id="summaryDiscountAmount">-$0.00</strong>
              </div>
              <div class="billing-summary-item total-row">
                <span>Total Revenue</span>
                <strong id="summaryTotalAmount">$0.00</strong>
              </div>
            </div>
          </div>

          <div class="panel">
            <div class="panel-header">
              <h3>Payment Methods</h3>
            </div>

            <div class="payment-method-list">
              <div class="payment-method-item">
                <i class="fa-regular fa-credit-card"></i>
                <div>
                  <h4>Card Payments</h4>
                  <p>Visa, MasterCard, debit and credit support</p>
                </div>
              </div>

              <div class="payment-method-item">
                <i class="fa-solid fa-wallet"></i>
                <div>
                  <h4>Digital Wallet</h4>
                  <p>Wallet-based quick online settlements</p>
                </div>
              </div>

              <div class="payment-method-item">
                <i class="fa-solid fa-building-columns"></i>
                <div>
                  <h4>Bank Transfer</h4>
                  <p>Secure account transfer and verification</p>
                </div>
              </div>

              <div class="payment-method-item">
                <i class="fa-solid fa-money-bill-wave"></i>
                <div>
                  <h4>Cash Payment</h4>
                  <p>Front desk manual collection support</p>
                </div>
              </div>
            </div>
          </div>

          <div class="panel">
            <div class="panel-header">
              <h3>Quick Billing Actions</h3>
            </div>

            <div class="quick-links">
              <a href="#" class="quick-link">Print Invoice</a>
              <a href="#" class="quick-link">Download PDF</a>
              <a href="#" class="quick-link">Apply Discount</a>
              <a href="#" class="quick-link">Process Refund</a>
              <a href="#" class="quick-link">Collect Deposit</a>
              <a href="#" class="quick-link">Final Settlement</a>
            </div>
          </div>

          <div class="panel">
            <div class="panel-header">
              <h3>Payment Alerts</h3>
            </div>

            <div class="notification-list">
              <div class="notification-item">
                <i class="fa-solid fa-circle-exclamation"></i>
                <div>
                  <h4>Pending Checkout Payment</h4>
                  <p>2 guest bills are waiting for final settlement.</p>
                </div>
              </div>

              <div class="notification-item">
                <i class="fa-solid fa-receipt"></i>
                <div>
                  <h4>Invoice Generated</h4>
                  <p>New invoice INV-1005 was generated successfully.</p>
                </div>
              </div>

              <div class="notification-item">
                <i class="fa-solid fa-arrow-rotate-left"></i>
                <div>
                  <h4>Refund Request</h4>
                  <p>One refund request is waiting for approval.</p>
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
const checkedInGuestSelect = document.getElementById("checkedInGuestSelect");
const billingBookingId = document.getElementById("billingBookingId");
const billingRoomNumber = document.getElementById("billingRoomNumber");
const billingCheckInDate = document.getElementById("billingCheckInDate");
const billingCheckOutDate = document.getElementById("billingCheckOutDate");
const billingNightsStayed = document.getElementById("billingNightsStayed");
async function loadCheckedInGuests() {
  try {
    const response = await fetch("backend/api/get_checkedin_guests.php");
    const result = await response.json();

    if (result.success && checkedInGuestSelect) {
      checkedInGuestSelect.innerHTML = '<option value="">Select checked-in guest</option>';

      result.data.forEach((guest) => {
        const option = document.createElement("option");
        option.value = guest.id;
        option.textContent = `${guest.full_name} - Room ${guest.assigned_room_number}`;
        checkedInGuestSelect.appendChild(option);
      });
    }
  } catch (error) {
    console.error(error);
  }
}
async function loadBillingBookingDetails(bookingId) {
  if (!bookingId) return;

  try {
    const response = await fetch(`backend/api/get_billing_booking_details.php?booking_id=${encodeURIComponent(bookingId)}`);
    const result = await response.json();

    if (result.success) {
      document.getElementById("invoiceGuest").value = result.data.guest_name;
      document.getElementById("invoiceId").value = result.data.invoice_id;
      document.getElementById("roomCharge").value = result.data.room_charge;
      loadGuestExtraCharges(result.data.booking_id);
      
      document.getElementById("depositAmount").value = result.data.deposit_amount;
      updateBillSummary();
      
      document.getElementById("billingNotes").value = result.data.billing_notes;

      billingBookingId.value = result.data.booking_id;
      billingRoomNumber.value = result.data.room_number;
      billingCheckInDate.value = result.data.check_in_date;
      billingCheckOutDate.value = result.data.check_out_date;
      billingNightsStayed.value = result.data.nights_stayed;
    } else {
      alert(result.message);
    }
  } catch (error) {
    console.error(error);
  }
}
if (checkedInGuestSelect) {
  checkedInGuestSelect.addEventListener("change", function () {
    const bookingId = this.value;

    if (bookingId) {
      loadBillingBookingDetails(bookingId);
    } else {
      document.getElementById("invoiceGuest").value = "";
      document.getElementById("invoiceId").value = "";
      document.getElementById("roomCharge").value = "";
      document.getElementById("depositAmount").value = "";
      document.getElementById("billingNotes").value = "";

      document.getElementById("activityCharge").value = "";
      document.getElementById("serviceCharge").value = "";
      updateBillSummary();

      billingBookingId.value = "";
      billingRoomNumber.value = "";
      billingCheckInDate.value = "";
      billingCheckOutDate.value = "";
      billingNightsStayed.value = "";
    }
  });
}
const billingForm = document.getElementById("billingForm");

async function loadBills() {
  try {
    const response = await fetch("backend/api/get_bills.php");
    const result = await response.json();

    if (result.success) {
      const tableBody = document.getElementById("billingTableBody");
      tableBody.innerHTML = "";

      result.data.forEach((bill) => {
        let statusClass = "pending";
        if (bill.invoice_status === "Paid") statusClass = "confirmed";
        if (bill.invoice_status === "Partially Paid") statusClass = "partial-status";
        if (bill.invoice_status === "Refunded") statusClass = "refund-status";

        const row = `
          <tr>
            <td>${bill.invoice_id}</td>
            <td>${bill.guest_name}</td>
            <td>$${bill.total_amount}</td>
            <td>${bill.payment_method}</td>
            <td><span class="status ${statusClass}">${bill.invoice_status}</span></td>
            <td><a href="#" class="table-link">View</a></td>
          </tr>
        `;
        tableBody.innerHTML += row;
      });
    } else {
      alert(result.message);
    }
  } catch (error) {
    console.error(error);
    alert("Server error while loading invoices.");
  }
}

async function loadBillingStats() {
  try {
    const response = await fetch("backend/api/billing_stats.php");
    const result = await response.json();

    if (result.success) {
      document.getElementById("totalInvoicesCount").textContent = result.data.total_invoices;
      document.getElementById("paidBillsCount").textContent = result.data.paid_bills;
      document.getElementById("pendingBillsCount").textContent = result.data.pending_bills;
      document.getElementById("revenueTodayCount").textContent = `$${result.data.revenue_today}`;
    }
  } catch (error) {
    console.error(error);
  }
}

if (billingForm) {
  billingForm.addEventListener("submit", async function (e) {
    e.preventDefault();

    const formData = new FormData(billingForm);

    try {
      const response = await fetch("backend/api/bill_create.php", {
        method: "POST",
        body: formData
      });

      const result = await response.json();


      if (result.success) {
        alert(result.message);
        billingForm.reset();
        
        billingBookingId.value = "";
        billingRoomNumber.value = "";
        billingCheckInDate.value = "";
        billingCheckOutDate.value = "";
        billingNightsStayed.value = "";

        if (checkedInGuestSelect) {
          checkedInGuestSelect.value = "";
        }
        
        loadBills();
        loadBillingStats();
        loadCheckedInGuests();
      
      } else {
        alert(result.message);
      }
    } catch (error) {
      console.error(error);
      alert("Server error while saving invoice.");
    }
  });
}

async function loadGuestExtraCharges(bookingId) {
  if (!bookingId) return;

  try {
    const response = await fetch(
      `backend/api/get_guest_extra_charges.php?booking_id=${encodeURIComponent(bookingId)}`
    );

    const result = await response.json();

    if (result.success) {
      document.getElementById("activityCharge").value =
        parseFloat(result.data.activity_charge || 0).toFixed(2);

      document.getElementById("serviceCharge").value =
        parseFloat(result.data.service_charge || 0).toFixed(2);

      updateBillSummary();
    } else {
      document.getElementById("activityCharge").value = "0.00";
      document.getElementById("serviceCharge").value = "0.00";
      updateBillSummary();
      console.warn(result.message);
    }
  } catch (error) {
    console.error(error);
    document.getElementById("activityCharge").value = "0.00";
    document.getElementById("serviceCharge").value = "0.00";
    updateBillSummary();
  }
}
function getNumberValue(id) {
  return parseFloat(document.getElementById(id)?.value || 0) || 0;
}

function updateBillSummary() {
  const roomCharge = getNumberValue("roomCharge");
  const activityCharge = getNumberValue("activityCharge");
  const serviceCharge = getNumberValue("serviceCharge");
  const taxAmount = getNumberValue("taxAmount");
  const depositAmount = getNumberValue("depositAmount");
  const discountAmount = getNumberValue("discountAmount");
  const refundAmount = getNumberValue("refundAmount");

  const total =
    roomCharge +
    activityCharge +
    serviceCharge +
    taxAmount -
    depositAmount -
    discountAmount -
    refundAmount;

  document.getElementById("summaryRoomCharge").textContent = `$${roomCharge.toFixed(2)}`;
  document.getElementById("summaryActivityCharge").textContent = `$${activityCharge.toFixed(2)}`;
  document.getElementById("summaryServiceCharge").textContent = `$${serviceCharge.toFixed(2)}`;
  document.getElementById("summaryTaxAmount").textContent = `$${taxAmount.toFixed(2)}`;
  document.getElementById("summaryDepositAmount").textContent = `-$${depositAmount.toFixed(2)}`;
  document.getElementById("summaryDiscountAmount").textContent = `-$${discountAmount.toFixed(2)}`;
  document.getElementById("summaryTotalAmount").textContent = `$${total.toFixed(2)}`;
}

["roomCharge", "taxAmount", "depositAmount", "discountAmount", "refundAmount"].forEach((id) => {
          const input = document.getElementById(id);
          if (input) {
            input.addEventListener("input", updateBillSummary);
          }
        });

loadBills();
loadBillingStats();
loadCheckedInGuests();
updateBillSummary();
</script>
</body>
</html>