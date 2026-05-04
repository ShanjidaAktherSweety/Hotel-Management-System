<?php
require_once 'backend/helpers/role_check.php';
require_once 'backend/helpers/sidebar.php';
requireRole(['Admin', 'Manager', 'Accountant']);
$currentPage = 'reports.php';

$reportId = $_GET['report_id'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Report Details</title>
  <link rel="stylesheet" href="style.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body class="dashboard-body">

  <div class="dashboard-layout">

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

    <main class="dashboard-main">
      <header class="dashboard-header">
        <div>
          <h1>Report Details</h1>
          <p>View generated report information and management summary.</p>
        </div>

        <div class="header-actions">
          <a href="reports.php" class="hero-btn secondary">← Back to Reports</a>
        </div>
      </header>

      <section class="dashboard-content-grid reports-grid">
        <div class="dashboard-left">

          <div class="panel">
            <div class="panel-header">
              <h3>Generated Report Information</h3>
            </div>

            <div class="reports-summary-grid">
              <div class="report-summary-card">
                <h4>Report ID</h4>
                <p id="viewReportId">Loading...</p>
              </div>

              <div class="report-summary-card">
                <h4>Department</h4>
                <p id="viewDepartment">Loading...</p>
              </div>

              <div class="report-summary-card">
                <h4>Period</h4>
                <p id="viewPeriod">Loading...</p>
              </div>

              <div class="report-summary-card">
                <h4>Status</h4>
                <p id="viewStatus">Loading...</p>
              </div>

              <div class="report-summary-card">
                <h4>Format</h4>
                <p id="viewFormat">Loading...</p>
              </div>

              <div class="report-summary-card">
                <h4>Created At</h4>
                <p id="viewCreatedAt">Loading...</p>
              </div>
            </div>
          </div>

          <div class="panel">
            <div class="panel-header">
              <h3>Report Notes</h3>
            </div>

            <div class="assignment-list">
              <div class="assignment-item">
                <h4>Purpose</h4>
                <p>This report record helps management track generated operational and financial reporting activity.</p>
              </div>

              <div class="assignment-item">
                <h4>Use Case</h4>
                <p>Managers and administrators can review previously generated reports by department and time period.</p>
              </div>

              <div class="assignment-item">
                <h4>Future Upgrade</h4>
                <p>This page can later support PDF export, printable layout, or detailed department-specific analytics.</p>
              </div>
            </div>
          </div>

        </div>

        <div class="dashboard-right">
          <div class="panel">
            <div class="panel-header">
              <h3>Quick Actions</h3>
            </div>

            <div class="quick-links">
              <a href="reports.php" class="quick-link">Back to Reports</a>
              <a href="#" class="quick-link">Print Report</a>
              <a href="#" class="quick-link">Export PDF</a>
              <a href="#" class="quick-link">Management Review</a>
            </div>
          </div>
        </div>
      </section>
    </main>
  </div>

<script>
const reportId = <?php echo json_encode($reportId); ?>;

async function loadReportDetails() {
  if (!reportId) {
    alert("Missing report ID.");
    return;
  }

  try {
    const response = await fetch(`backend/api/get_report_details.php?report_id=${encodeURIComponent(reportId)}`);
    const result = await response.json();

    if (result.success) {
      document.getElementById("viewReportId").textContent = result.data.report_id;
      document.getElementById("viewDepartment").textContent = result.data.department;
      document.getElementById("viewPeriod").textContent = result.data.period_label;
      document.getElementById("viewStatus").textContent = result.data.report_status;
      document.getElementById("viewFormat").textContent = result.data.report_format;
      document.getElementById("viewCreatedAt").textContent = result.data.created_at;
    } else {
      alert(result.message);
    }
  } catch (error) {
    console.error(error);
    alert("Server error while loading report details.");
  }
}

loadReportDetails();
</script>
</body>
</html>