<?php
require_once 'backend/helpers/role_check.php';
require_once 'backend/helpers/sidebar.php';
requireRole(['Admin', 'Manager', 'Accountant', 'Housekeeping']);
$currentPage = 'inventory.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Hotel Management System - Inventory</title>
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
          <h1>Inventory Management</h1>
          <p>Track stock levels, supplier details, low stock alerts, and automatic usage deduction across hotel operations.</p>
        </div>

        <div class="header-actions">
          <div class="search-box">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" placeholder="Search item, category, or supplier..." />
          </div>
          <button type="button" class="hero-btn primary">+ Add Inventory Item</button>
        </div>
      </header>

      <!-- Stats -->
      <section class="stats-grid inventory-stats">
        <div class="stat-card">
          <div class="stat-icon blue"><i class="fa-solid fa-box-open"></i></div>
          <div>
            <h3>Total Items</h3>
            <p id="totalItemsCount">0</p>
          </div>
        </div>

        <div class="stat-card">
          <div class="stat-icon green"><i class="fa-solid fa-layer-group"></i></div>
          <div>
            <h3>Categories</h3>
            <p id="totalCategoriesCount">0</p>

          </div>
        </div>

        <div class="stat-card">
          <div class="stat-icon orange"><i class="fa-solid fa-triangle-exclamation"></i></div>
          <div>
            <h3>Low Stock Alerts</h3>
            <p id="lowStockAlertsCount">0</p>

          </div>
        </div>

        <div class="stat-card">
          <div class="stat-icon purple"><i class="fa-solid fa-truck-field"></i></div>
          <div>
            <h3>Suppliers</h3>
            <p id="suppliersCount">0</p>
          </div>
        </div>
      </section>

      <!-- Filters -->
      <section class="panel inventory-filter-panel">
        <div class="panel-header">
          <h3>Inventory Filters</h3>
        </div>

        <div class="inventory-filters">
          <select>
            <option>All Categories</option>
            <option>Toiletries</option>
            <option>Cleaning Items</option>
            <option>Food & Beverage</option>
            <option>Activity Equipment</option>
          </select>

          <select>
            <option>All Stock Status</option>
            <option>In Stock</option>
            <option>Low Stock</option>
            <option>Out of Stock</option>
          </select>

          <select>
            <option>All Suppliers</option>
            <option>Fresh Supply Co.</option>
            <option>CleanCare Ltd.</option>
            <option>Aqua Sports Hub</option>
            <option>Hotel Essentials BD</option>
          </select>

          <button type="button" class="filter-btn">Apply Filter</button>
        </div>
      </section>

      <section class="dashboard-content-grid inventory-grid">

        <!-- Left -->
        <div class="dashboard-left">

          <div class="panel">
            <div class="panel-header">
              <h3>Add / Update Inventory Item</h3>
              <a href="#">Save Draft</a>
            </div>

            <form class="inventory-form" id="inventoryForm">
              <div class="form-grid">
                <div class="form-group">
                  <label for="itemName">Item Name</label>
                  <input type="text" id="itemName"name="item_name" placeholder="Enter item name" required />
                </div>

                <div class="form-group">
                  <label for="itemCategory">Category</label>
                  <select id="itemCategory" name="category" required>
                    <option value="">Select category</option>
                    <option>Toiletries</option>
                    <option>Cleaning Items</option>
                    <option>Food & Beverage</option>
                    <option>Activity Equipment</option>
                  </select>
                </div>

                <div class="form-group">
                  <label for="itemQuantity">Current Quantity</label>
                  <input type="number" id="itemQuantity" name="quantity" placeholder="Enter quantity" required />
                </div>

                <div class="form-group">
                  <label for="minimumStock">Minimum Stock Level</label>
                  <input type="number" id="minimumStock" name="minimum_stock" placeholder="Enter minimum stock" required />
                </div>

                <div class="form-group">
                  <label for="unitType">Unit Type</label>
                  <select id="unitType" name="unit" required>
                    <option value="">Select unit</option>
                    <option>Pieces</option>
                    <option>Packets</option>
                    <option>Bottles</option>
                    <option>Kilograms</option>
                    <option>Liters</option>
                  </select>
                </div>

                <div class="form-group">
                  <label for="supplierName">Supplier</label>
                  <select id="supplierName" name="supplier_name" required>
                    <option value="">Select supplier</option>
                    <option>Fresh Supply Co.</option>
                    <option>CleanCare Ltd.</option>
                    <option>Aqua Sports Hub</option>
                    <option>Hotel Essentials BD</option>
                  </select>
                </div>

                <div class="form-group">
                  <label for="reorderDate">Reorder Date</label>
                  <input type="date" id="reorderDate" name="reorder_date" />
                </div>

                <div class="form-group">
                  <label for="deductionSource">Auto Deduction Source</label>
                  <select id="deductionSource" name="deduction_source">
                    <option value="">Select source</option>
                    <option>Room Usage</option>
                    <option>Housekeeping Usage</option>
                    <option>Restaurant Orders</option>
                    <option>Activity Equipment Usage</option>
                  </select>
                </div>

                <div class="form-group full-width">
                  <label for="inventoryNotes">Inventory Notes</label>
                  <textarea id="inventoryNotes" name="inventory_notes" rows="4" placeholder="Add item notes, supplier details, delivery remarks, or stock usage information"></textarea>
                </div>
              </div>

              <div class="booking-checkbox-row">
                <label><input type="checkbox" name="low_stock_alert" /> Enable Low Stock Alert</label>
                <label><input type="checkbox" name="auto_deduct_on_usage" /> Auto Deduct on Usage</label>
                <label><input type="checkbox" name="reorder_reminder_active"/> Reorder Reminder Active</label>
                <label><input type="checkbox" name="supplier_notified" /> Supplier Notified</label>
              </div>

              <div class="booking-form-actions">
                <button type="reset" class="secondary-btn-small room-btn">Reset</button>
                <button type="submit" class="primary-btn-small room-btn">Save Inventory Item</button>
              </div>
            </form>
          </div>

          <div class="panel">
            <div class="panel-header">
              <h3>Inventory Stock Table</h3>
              <a href="#">Export</a>
            </div>

            <div class="table-responsive">
              <table class="dashboard-table">
                <thead>
                  <tr>
                    <th>Item</th>
                    <th>Category</th>
                    <th>Qty</th>
                    <th>Min Stock</th>
                    <th>Status</th>
                    <th>Supplier</th>
                  </tr>
                </thead>
                <tbody id="inventoryTableBody"></tbody>
                
              </table>
            </div>
          </div>

        </div>

        <!-- Right -->
        <div class="dashboard-right">

          <div class="panel">
            <div class="panel-header">
              <h3>Category Overview</h3>
            </div>

            <div class="inventory-category-list">
              <div class="inventory-category-item">
                <i class="fa-solid fa-pump-soap"></i>
                <div>
                  <h4>Toiletries</h4>
                  <p>Guest room amenities and bathroom supplies</p>
                </div>
              </div>

              <div class="inventory-category-item">
                <i class="fa-solid fa-spray-can-sparkles"></i>
                <div>
                  <h4>Cleaning Items</h4>
                  <p>Housekeeping materials and sanitation supplies</p>
                </div>
              </div>

              <div class="inventory-category-item">
                <i class="fa-solid fa-bowl-food"></i>
                <div>
                  <h4>Food & Beverage</h4>
                  <p>Restaurant ingredients, drinks, and kitchen stock</p>
                </div>
              </div>

              <div class="inventory-category-item">
                <i class="fa-solid fa-helmet-safety"></i>
                <div>
                  <h4>Activity Equipment</h4>
                  <p>Zipline and swimming related equipment items</p>
                </div>
              </div>
            </div>
          </div>

          <div class="panel">
            <div class="panel-header">
              <h3>Low Stock Alerts</h3>
            </div>

            <div class="notification-list">
              <div class="notification-item">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <div>
                  <h4>Bath Towels Running Low</h4>
                  <p>Current quantity is below the required minimum level.</p>
                </div>
              </div>

              <div class="notification-item">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <div>
                  <h4>Salmon Fillet Low Stock</h4>
                  <p>Restaurant inventory needs immediate replenishment.</p>
                </div>
              </div>

              <div class="notification-item">
                <i class="fa-solid fa-ban"></i>
                <div>
                  <h4>Safety Harness Out of Stock</h4>
                  <p>Zipline activity stock unavailable until reorder is completed.</p>
                </div>
              </div>
            </div>
          </div>

          <div class="panel">
            <div class="panel-header">
              <h3>Supplier Management</h3>
            </div>

            <div class="assignment-list">
              <div class="assignment-item">
                <h4>Fresh Supply Co.</h4>
                <p>Provides meat, fish, vegetables, and restaurant stock.</p>
              </div>
              <div class="assignment-item">
                <h4>CleanCare Ltd.</h4>
                <p>Supplies cleaning liquids, disinfectants, and housekeeping materials.</p>
              </div>
              <div class="assignment-item">
                <h4>Aqua Sports Hub</h4>
                <p>Handles swimming and zipline equipment supply.</p>
              </div>
              <div class="assignment-item">
                <h4>Hotel Essentials BD</h4>
                <p>Provides toiletries, towels, and room use items.</p>
              </div>
            </div>
          </div>

          <div class="panel">
            <div class="panel-header">
              <h3>Quick Inventory Actions</h3>
            </div>

            <div class="quick-links">
              <a href="#" class="quick-link">Reorder Stock</a>
              <a href="#" class="quick-link">View Suppliers</a>
              <a href="#" class="quick-link">Usage Report</a>
              <a href="#" class="quick-link">Auto Deduction Log</a>
              <a href="#" class="quick-link">Add New Category</a>
              <a href="#" class="quick-link">Stock Adjustment</a>
            </div>
          </div>

        </div>
      </section>

    </main>
  </div>

<script src="script.js"></script>
<script>
const inventoryForm = document.getElementById("inventoryForm");

async function loadInventory() {
  try {
    const response = await fetch("backend/api/get_inventory.php");
    const result = await response.json();

    if (result.success) {
      const tableBody = document.getElementById("inventoryTableBody");
      tableBody.innerHTML = "";

      result.data.forEach((item) => {
        let statusText = "In Stock";
        let statusClass = "in-stock";

        if (parseInt(item.quantity) <= 0) {
          statusText = "Out of Stock";
          statusClass = "out-stock";
        } else if (parseInt(item.quantity) <= parseInt(item.minimum_stock)) {
          statusText = "Low Stock";
          statusClass = "low-stock";
        }

        const row = `
          <tr>
            <td>${item.item_name}</td>
            <td>${item.category}</td>
            <td>${item.quantity}</td>
            <td>${item.minimum_stock}</td>
            <td><span class="inventory-badge ${statusClass}">${statusText}</span></td>
            <td>${item.supplier_name ? item.supplier_name : '-'}</td>
          </tr>
        `;

        tableBody.innerHTML += row;
      });
    } else {
      alert(result.message);
    }
  } catch (error) {
    console.error(error);
    alert("Server error while loading inventory.");
  }
}

async function loadInventoryStats() {
  try {
    const response = await fetch("backend/api/get_inventory.php");
    const result = await response.json();

    if (result.success) {
      const items = result.data;

      document.getElementById("totalItemsCount").textContent = items.length;

      const categories = [...new Set(items.map(item => item.category))];
      document.getElementById("totalCategoriesCount").textContent = categories.length;

      const lowStockItems = items.filter(item => parseInt(item.quantity) <= parseInt(item.minimum_stock));
      document.getElementById("lowStockAlertsCount").textContent = lowStockItems.length;

      const suppliers = [...new Set(items.map(item => item.supplier_name).filter(name => name && name !== ''))];
      document.getElementById("suppliersCount").textContent = suppliers.length;
    }
  } catch (error) {
    console.error(error);
  }
}

if (inventoryForm) {
  inventoryForm.addEventListener("submit", async function (e) {
    e.preventDefault();

    const formData = new FormData(inventoryForm);

    try {
      const response = await fetch("backend/api/add_inventory.php", {
        method: "POST",
        body: formData
      });

      const result = await response.json();

      if (result.success) {
        alert(result.message);
        inventoryForm.reset();
        loadInventory();
        loadInventoryStats();
      } else {
        alert(result.message);
      }
    } catch (error) {
      console.error(error);
      alert("Server error while saving inventory item.");
    }
  });
}

loadInventory();
loadInventoryStats();
</script>
</body>
</html>