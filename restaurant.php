<?php
require_once 'backend/helpers/role_check.php';
require_once 'backend/helpers/sidebar.php';
requireRole(['Admin', 'Manager', 'Receptionist']);
$currentPage = 'restaurant.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Hotel Management System - Restaurant</title>
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
          <h1>Restaurant & Room Service Management</h1>
          <p>Manage dine-in orders, room service requests, menu items, kitchen tickets, and billing integration.</p>
        </div>

        <div class="header-actions">
          <div class="search-box">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" placeholder="Search order, room, or item..." />
          </div>
          <button type="button" class="hero-btn primary">+ New Order</button>
        </div>
      </header>

      <!-- Stats -->
      <section class="stats-grid restaurant-stats">
        <div class="stat-card">
          <div class="stat-icon blue"><i class="fa-solid fa-utensils"></i></div>
          <div>
            <h3>Total Orders</h3>
            <p id="restaurantTotalOrders">0</p>

          </div>
        </div>

        <div class="stat-card">
          <div class="stat-icon purple"><i class="fa-solid fa-bell-concierge"></i></div>
          <div>
            <h3>Room Service</h3>
            <p id="restaurantRoomService">0</p>

          </div>
        </div>

        <div class="stat-card">
          <div class="stat-icon orange"><i class="fa-solid fa-fire-burner"></i></div>
          <div>
            <h3>Kitchen Queue</h3>
            <p id="restaurantKitchenQueue">0</p>

          </div>
        </div>

        <div class="stat-card">
          <div class="stat-icon green"><i class="fa-solid fa-sack-dollar"></i></div>
          <div>
            <h3>Restaurant Revenue</h3>
            <p id="restaurantRevenue">$0</p>
          </div>
        </div>
      </section>

      <div class="panel">
        <div class="panel-header">
          <h3>Add New Menu Item</h3>
        </div>
        
        <form id="menuItemForm" enctype="multipart/form-data">
          <input type="hidden" name="menu_item_id" id="menuItemId">
          <div class="form-grid">
            <div class="form-group">
              <label>Item Name</label>
              <input type="text" name="item_name" required>
            </div>
            
            <div class="form-group">
              <label>Category</label>
              <select name="category" required>
                <option value="">Select category</option>
                <option>Breakfast</option>
                <option>Lunch</option>
                <option>Dinner</option>
                <option>Desserts</option>
                <option>Drinks</option>
                <option>Room Service</option>
              </select>
            </div>
            
            <div class="form-group">
              <label>Price</label>
              <input type="number" name="price" step="0.01" required>
            </div>
            
            <div class="form-group">
              <label>Status</label>
              <select name="item_status" required>
                <option>Available</option>
                <option>Unavailable</option>
              </select>
            </div>
            
            <div class="form-group">
              <label>Image</label>
              <input type="file" name="menu_image" accept="image/*">
            </div>
            
            <div class="form-group full-width">
              <label>Description</label>
              <textarea name="description" rows="4" required></textarea>
            </div>
          </div>
          
          <div class="booking-form-actions">
            <button type="submit" class="primary-btn-small room-btn">Save Menu Item</button>
          </div>
        </form>
      </div>


      <!-- Menu Showcase -->
      <section class="restaurant-menu-showcase">
        <div class="panel">
          <div class="panel-header">
            <h3>Featured Menu Items</h3>
            <a href="#">View Full Menu</a>
          </div>
          <div class="restaurant-menu-grid" id="restaurantMenuGrid"></div>
        </div>
        </section>

      <section class="dashboard-content-grid restaurant-grid">

        <!-- Left -->
        <div class="dashboard-left">

          <div class="panel">
            <div class="panel-header">
              <h3>Create Food Order</h3>
              <a href="#">Save Draft</a>
            </div>

            <form class="restaurant-form" id="restaurantForm">
              <input type="hidden" id="restaurantBookingId" name="booking_id">
              
              <div class="form-grid">
                <div class="form-group">
                  <label for="orderType">Order Type</label>
                  <select id="orderType" name="order_type" required>
                    <option value="">Select order type</option>
                    <option>Dine-In</option>
                    <option>Room Service</option>
                    <option>Takeaway</option>
                  </select>
                </div>

                
                
                <div class="form-group">
                  <label for="restaurantCheckedInGuest">Checked-In Guest</label>
                  <select id="restaurantCheckedInGuest" required>
                    <option value="">Select checked-in guest</option>
                  </select>
                </div>
                
                <div class="form-group">
                  <label for="guestNameRestaurant">Guest / Customer Name</label>
                  <input type="text" id="guestNameRestaurant" name="guest_name" placeholder="Guest name" readonly required />
                </div>

                <div class="form-group">
                  <label for="roomTableRef">Room No / Table No</label>
                  <input type="text" id="roomTableRef" name="room_table_ref" placeholder="Room number / Table number" required />
                </div>

                <div class="form-group">
                  <label for="menuItem">Menu Item</label>
                  <select id="menuItem" name="menu_item" required>
                    <option value="">Loading menu items...</option>
                  </select>
                </div>

                <div class="form-group">
                  <label for="quantity">Quantity</label>
                  <input type="number" id="quantity" name="quantity" min="1" value="1" required />
                </div>

                <div class="form-group">
                  <label for="priorityLevel">Kitchen Priority</label>
                  <select id="priorityLevel" name="kitchen_priority" required>
                    <option value="">Select priority</option>
                    <option>Normal</option>
                    <option>High</option>
                    <option>Urgent</option>
                  </select>
                </div>

                <div class="form-group">
                  <label for="orderStatus">Order Status</label>
                  <select id="orderStatus" name="order_status" required>
                    <option value="">Select status</option>
                    <option>Pending</option>
                    <option>Preparing</option>
                    <option>Ready to Serve</option>
                    <option>Served</option>
                  </select>
                </div>

                <div class="form-group">
                  <label for="kotNumber">KOT Number</label>
                  <input type="text" id="kotNumber" name="kot_number" placeholder="KOT-1001" required />
                </div>

                <div class="form-group">
                  <label for="restaurantCharge">Item Charge</label>
                  <input type="number" id="restaurantCharge" name="item_charge" placeholder="Enter total item charge" required />
                </div>

                <div class="form-group">
                  <label for="billingOptionRestaurant">Billing Option</label>
                  <select id="billingOptionRestaurant" name="billing_option" required>
                    <option value="">Select billing option</option>
                    <option>Add to Guest Room Bill</option>
                    <option>Direct Restaurant Payment</option>
                  </select>
                </div>

                <div class="form-group full-width">
                  <label for="specialInstruction">Special Instruction</label>
                  <textarea id="specialInstruction" name="special_instruction" rows="4" placeholder="Add notes for kitchen, allergies, no spice, extra sauce, late-night delivery, etc."></textarea>
                </div>
              </div>

              <div class="booking-checkbox-row">
                <label><input type="checkbox" name="send_to_kitchen" /> Send to Kitchen</label>
                <label><input type="checkbox" name="add_to_room_bill" /> Add to Room Bill</label>
                <label><input type="checkbox" name="print_kot"/> Print KOT</label>
                <label><input type="checkbox" name="notify_service_staff" /> Notify Service Staff</label>
              </div>

              <div class="booking-form-actions">
                <button type="reset" class="secondary-btn-small room-btn">Reset</button>
                <button type="submit" class="primary-btn-small room-btn">Save Order</button>
              </div>
            </form>
          </div>

          <div class="panel">
            <div class="panel-header">
              <h3>Recent Orders</h3>
              <a href="#">View All</a>
            </div>

            <div class="table-responsive">
              <table class="dashboard-table">
                <thead>
                  <tr>
                    <th>Order ID</th>
                    <th>Guest / Ref</th>
                    <th>Type</th>
                    <th>Item</th>
                    <th>Status</th>
                    <th>Bill</th>
                  </tr>
                </thead>
                <tbody id="restaurantOrdersTableBody"></tbody>
                
              </table>
            </div>
          </div>

        </div>

        <!-- Right -->
        <div class="dashboard-right">

          <div class="panel">
            <div class="panel-header">
              <h3>Menu Categories</h3>
              <a href="#">Manage Menu</a>
            </div>

            <div class="quick-links">
              <a href="#" class="quick-link">Breakfast</a>
              <a href="#" class="quick-link">Lunch</a>
              <a href="#" class="quick-link">Dinner</a>
              <a href="#" class="quick-link">Desserts</a>
              <a href="#" class="quick-link">Drinks</a>
              <a href="#" class="quick-link">Room Service</a>
            </div>
          </div>

          <div class="panel">
            <div class="panel-header">
              <h3>Kitchen Order Tickets</h3>
            </div>

            <div class="assignment-list">
              <div class="assignment-item">
                <h4>KOT-1001</h4>
                <p>Room 205 - Grilled Salmon - High Priority</p>
              </div>
              <div class="assignment-item">
                <h4>KOT-1002</h4>
                <p>Table 4 - Veg Pasta - Normal Priority</p>
              </div>
              <div class="assignment-item">
                <h4>KOT-1003</h4>
                <p>Room 118 - Caesar Salad - Normal Priority</p>
              </div>
              <div class="assignment-item">
                <h4>KOT-1004</h4>
                <p>Table 2 - Lava Cake - Ready to Serve</p>
              </div>
            </div>
          </div>

          <div class="panel">
            <div class="panel-header">
              <h3>Top Selling Items</h3>
            </div>
            <div class="equipment-list" id="topSellingItemsList"></div>

            
          </div>

          <div class="panel">
            <div class="panel-header">
              <h3>Restaurant Alerts</h3>
            </div>
            <div class="notification-list" id="restaurantAlertsList"></div>

            
          </div>

        </div>
      </section>

    </main>
  </div>

<script src="script.js"></script>
<script>
const restaurantForm = document.getElementById("restaurantForm");
const restaurantCheckedInGuest = document.getElementById("restaurantCheckedInGuest");
const restaurantBookingId = document.getElementById("restaurantBookingId");
const guestNameRestaurant = document.getElementById("guestNameRestaurant");
const roomTableRef = document.getElementById("roomTableRef");

async function loadRestaurantCheckedInGuests() {
  try {
    const response = await fetch("backend/api/get_checkedin_guests.php");
    const result = await response.json();

    if (!restaurantCheckedInGuest) return;

    restaurantCheckedInGuest.innerHTML = '<option value="">Select checked-in guest</option>';

    if (result.success && result.data.length > 0) {
      result.data.forEach((guest) => {
        const option = document.createElement("option");

        option.value = guest.id;
        option.textContent = `${guest.full_name} - Room ${guest.assigned_room_number}`;

        option.setAttribute("data-name", guest.full_name);
        option.setAttribute("data-room", guest.assigned_room_number);

        restaurantCheckedInGuest.appendChild(option);
      });
    }
  } catch (error) {
    console.error(error);
  }
}

if (restaurantCheckedInGuest) {
  restaurantCheckedInGuest.addEventListener("change", function () {
    const selectedOption = this.options[this.selectedIndex];

    if (this.value) {
      restaurantBookingId.value = this.value;
      guestNameRestaurant.value = selectedOption.getAttribute("data-name") || "";
      roomTableRef.value = selectedOption.getAttribute("data-room") || "";
    } else {
      restaurantBookingId.value = "";
      guestNameRestaurant.value = "";
      roomTableRef.value = "";
    }
  });
}
async function loadRestaurantOrders() {
  try {
    const response = await fetch("backend/api/get_restaurant_orders.php");
    const result = await response.json();

    if (result.success) {
      const tableBody = document.getElementById("restaurantOrdersTableBody");
      tableBody.innerHTML = "";

      result.data.forEach((order) => {
        let statusClass = "pending";
        if (order.order_status === "Served") statusClass = "confirmed";
        if (order.order_status === "Preparing") statusClass = "checked";
        if (order.order_status === "Ready to Serve") statusClass = "ready-status";

        let billClass = "direct";
        let billText = "Direct Pay";

        if (order.billing_option === "Add to Guest Room Bill") {
          billClass = "added";
          billText = "Added to Room";
        }

        const row = `
          <tr>
            <td>${order.order_id}</td>
            <td>${order.guest_name} / ${order.room_table_ref}</td>
            <td>${order.order_type}</td>
            <td>${order.menu_item}</td>
            <td><span class="status ${statusClass}">${order.order_status}</span></td>
            <td><span class="restaurant-bill-tag ${billClass}">${billText}</span></td>
          </tr>
        `;

        tableBody.innerHTML += row;
      });
    } else {
      alert(result.message);
    }
  } catch (error) {
    console.error(error);
    alert("Server error while loading restaurant orders.");
  }
}



async function loadRestaurantStats() {
  try {
    const response = await fetch("backend/api/restaurant_stats.php");
    const result = await response.json();

    if (result.success) {
      document.getElementById("restaurantTotalOrders").textContent = result.data.total_orders;
      document.getElementById("restaurantRoomService").textContent = result.data.room_service;
      document.getElementById("restaurantKitchenQueue").textContent = result.data.kitchen_queue;
      document.getElementById("restaurantRevenue").textContent = "$" + parseFloat(result.data.restaurant_revenue).toFixed(2);
    }
  } catch (error) {
    console.error(error);
  }
}

if (restaurantForm) {
  restaurantForm.addEventListener("submit", async function (e) {
    e.preventDefault();

    const formData = new FormData(restaurantForm);

    try {
      const response = await fetch("backend/api/restaurant_order_create.php", {
        method: "POST",
        body: formData
      });

      const result = await response.json();

      if (result.success) {
        alert(result.message + " (" + result.data.order_id + ")");
        restaurantForm.reset();
        restaurantBookingId.value = "";
        if (restaurantCheckedInGuest) restaurantCheckedInGuest.value = "";
        loadRestaurantOrders();
        loadRestaurantStats();
        loadTopSellingItems();
        loadRestaurantAlerts();
      } else {
        alert(result.message);
      }
    } catch (error) {
      console.error(error);
      alert("Server error while saving restaurant order.");
    }
  });
}

async function loadTopSellingItems() {
  try {
    const response = await fetch("backend/api/get_top_selling_items.php");
    const result = await response.json();

    const topSellingItemsList = document.getElementById("topSellingItemsList");
    if (!topSellingItemsList) return;

    topSellingItemsList.innerHTML = "";

    if (result.success && result.data.length > 0) {
      result.data.forEach((item) => {
        const row = `
          <div class="equipment-item">
            <span>${item.menu_item}</span>
            <strong>${item.total_orders} Orders</strong>
          </div>
        `;
        topSellingItemsList.innerHTML += row;
      });
    } else {
      topSellingItemsList.innerHTML = `
        <div class="equipment-item">
          <span>No sales data</span>
          <strong>0 Orders</strong>
        </div>
      `;
    }
  } catch (error) {
    console.error(error);
  }
}
async function loadRestaurantAlerts() {
  try {
    const response = await fetch("backend/api/get_restaurant_alerts.php");
    const result = await response.json();

    const restaurantAlertsList = document.getElementById("restaurantAlertsList");
    if (!restaurantAlertsList) return;

    restaurantAlertsList.innerHTML = "";

    if (result.success && result.data.length > 0) {
      result.data.forEach((item) => {
        const row = `
          <div class="notification-item">
            <i class="fa-solid ${item.icon}"></i>
            <div>
              <h4>${item.title}</h4>
              <p>${item.message}</p>
            </div>
          </div>
        `;
        restaurantAlertsList.innerHTML += row;
      });
    } else {
      restaurantAlertsList.innerHTML = `
        <div class="notification-item">
          <i class="fa-solid fa-circle-check"></i>
          <div>
            <h4>No Restaurant Alerts</h4>
            <p>Restaurant operations are running normally.</p>
          </div>
        </div>
      `;
    }
  } catch (error) {
    console.error(error);
  }
}


async function loadMenuItems() {
  try {
    const response = await fetch("backend/api/get_menu_items.php");
    const result = await response.json();

    const menuGrid = document.getElementById("restaurantMenuGrid");
    if (!menuGrid) return;

    menuGrid.innerHTML = "";

    if (result.success && result.data.length > 0) {
      result.data.forEach((item) => {
        const image = item.image_path ? item.image_path : "default-food.jpg";

        menuGrid.innerHTML += `
          <div class="food-card">
            <img src="${image}" alt="${item.item_name}">
            <div class="food-card-body">
              <div class="food-card-top">
                <h4>${item.item_name}</h4>
                <span>$${parseFloat(item.price).toFixed(2)}</span>
              </div>
              <p>${item.description}</p>
              <div class="food-card-actions">
                <button type="button" class="room-btn primary-btn-small" onclick="editMenuItem(${item.id})">Edit</button>
                <button type="button" class="room-btn secondary-btn-small" onclick="deleteMenuItem(${item.id})">Delete</button>
              </div>
              
            </div>
          </div>
        `;
      });
    } else {
      menuGrid.innerHTML = "<p>No menu items available.</p>";
    }
  } catch (error) {
    console.error(error);
  }
}

const menuItemForm = document.getElementById("menuItemForm");

if (menuItemForm) {
  menuItemForm.addEventListener("submit", async function(e) {
    e.preventDefault();

    const formData = new FormData(menuItemForm);
    const menuItemId = document.getElementById("menuItemId").value;

    const apiUrl = menuItemId
      ? "backend/api/update_menu_item.php"
      : "backend/api/add_menu_item.php";

    try {
      const response = await fetch(apiUrl, {
        method: "POST",
        body: formData
      });

      const result = await response.json();
      alert(result.message);

      if (result.success) {
        menuItemForm.reset();
        document.getElementById("menuItemId").value = "";
        loadMenuItems();
        loadMenuItemDropdown();
      }
    } catch (error) {
      console.error(error);
      alert("Server error while saving menu item.");
    }
  });
}


async function deleteMenuItem(id) {
  if (!confirm("Are you sure you want to delete this menu item?")) return;

  const formData = new FormData();
  formData.append("id", id);

  try {
    const response = await fetch("backend/api/delete_menu_item.php", {
      method: "POST",
      body: formData
    });

    const result = await response.json();
    alert(result.message);

    if (result.success) {
      loadMenuItems();
      loadMenuItemDropdown();
    }
  } catch (error) {
    console.error(error);
    alert("Server error while deleting menu item.");
  }
}

async function editMenuItem(id) {
  try {
    const response = await fetch("backend/api/get_menu_items.php");
    const result = await response.json();

    if (!result.success) {
      alert(result.message);
      return;
    }

    const item = result.data.find(menu => parseInt(menu.id) === parseInt(id));

    if (!item) {
      alert("Menu item not found.");
      return;
    }

    document.getElementById("menuItemId").value = item.id;
    document.querySelector('[name="item_name"]').value = item.item_name;
    document.querySelector('[name="category"]').value = item.category;
    document.querySelector('[name="price"]').value = item.price;
    document.querySelector('[name="description"]').value = item.description;
    document.querySelector('[name="item_status"]').value = item.item_status;

    window.scrollTo({ top: 0, behavior: "smooth" });
  } catch (error) {
    console.error(error);
    alert("Server error while loading menu item.");
  }
}

async function loadMenuItemDropdown() {
  try {
    const response = await fetch("backend/api/get_menu_items.php");
    const result = await response.json();

    const menuSelect = document.getElementById("menuItem");
    if (!menuSelect) return;

    menuSelect.innerHTML = '<option value="">Select menu item</option>';

    if (result.success && result.data.length > 0) {
      result.data.forEach((item) => {
        if (item.item_status === "Available") {
          const option = document.createElement("option");
          option.value = item.item_name;
          option.textContent = `${item.item_name} - $${parseFloat(item.price).toFixed(2)}`;
          option.setAttribute("data-price", item.price);
          menuSelect.appendChild(option);
        }
      });
    } else {
      menuSelect.innerHTML = '<option value="">No menu items available</option>';
    }
  } catch (error) {
    console.error(error);
  }
}

const menuItemSelect = document.getElementById("menuItem");
const restaurantChargeInput = document.getElementById("restaurantCharge");
const quantityInput = document.getElementById("quantity");

function updateRestaurantCharge() {
  if (!menuItemSelect || !restaurantChargeInput || !quantityInput) return;

  const selectedOption = menuItemSelect.options[menuItemSelect.selectedIndex];
  const price = parseFloat(selectedOption?.getAttribute("data-price") || 0);
  const quantity = parseInt(quantityInput.value || 1);

  restaurantChargeInput.value = (price * quantity).toFixed(2);
}

if (menuItemSelect) {
  menuItemSelect.addEventListener("change", updateRestaurantCharge);
}

if (quantityInput) {
  quantityInput.addEventListener("input", updateRestaurantCharge);
}


loadRestaurantOrders();
loadRestaurantStats();
loadTopSellingItems();
loadRestaurantAlerts();
loadMenuItems();
loadMenuItemDropdown();
loadRestaurantCheckedInGuests();
</script>
</body>
</html>