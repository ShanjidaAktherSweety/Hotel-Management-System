<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Hotel Management System - Login</title>
  <link rel="stylesheet" href="style.css">
</head>
<body class="auth-body">
  <div class="auth-wrapper">
    <div class="auth-card">

      <div class="auth-left">
        <div class="brand">
          <div class="brand-logo">HM</div>
          <div>
            <h2>HotelSys</h2>
            <p>Management System</p>
          </div>
        </div>

        <div class="form-area">
          <h1>Staff Login</h1>
          <p class="subtitle">
            Access is restricted to authorized hotel users only. Please log in with your assigned credentials.
          </p>

          <form id="loginForm" class="auth-form">
            <div class="input-group">
              <label for="loginEmail">Username or Email</label>
              <input type="text" id="loginEmail" name="login" placeholder="Enter your username or email" required />
            </div>

            <div class="input-group">
              <label for="loginPassword">Password</label>
              <div class="password-box">
                <input type="password" id="loginPassword" name="password" placeholder="Enter your password" required />
                <span class="toggle-password" onclick="togglePassword('loginPassword', this)">👁</span>
              </div>
            </div>

            <div class="form-options">
              <label class="remember">
                <input type="checkbox" name="remember_me" />
                <span>Remember Me</span>
              </label>
            </div>

            <button type="submit" class="primary-btn">Log In</button>

            

            <p class="bottom-text">
              Authorized hotel staff only.
            </p>
          </form>
        </div>

        <div class="auth-footer-text">
          <span>Hotel Internal Management System</span>
          <span>Copyright 2026</span>
        </div>
      </div>

      <div class="auth-right">
        <div class="showcase-content">
          <div class="image-grid">
            <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=900&q=80" alt="Hotel Exterior">
            <img src="https://images.unsplash.com/photo-1578683010236-d716f9a3f461?auto=format&fit=crop&w=900&q=80" alt="Pool Area">
            <img src="https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=900&q=80" alt="Room Interior">
            <img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&w=900&q=80" alt="Luxury Hotel">
          </div>

          <div class="showcase-text">
            <h2>Professional Hotel Management Dashboard</h2>
            <p>
              Manage bookings, rooms, billing, housekeeping, restaurant services, inventory, and staff operations from one centralized platform.
            </p>
          </div>
        </div>
      </div>

    </div>
  </div>

<script src="script.js"></script>
<script>
  const loginForm = document.getElementById("loginForm");

  if (loginForm) {
    loginForm.addEventListener("submit", async function (e) {
      e.preventDefault();

      const formData = new FormData(loginForm);

      try {
        const response = await fetch("backend/api/staff_login.php", {
          method: "POST",
          body: formData
        });

        const result = await response.json();

        if (result.success) {
          alert(result.message);
          window.location.href = result.data.redirect;
        } else {
          alert(result.message);
        }
      } catch (error) {
        console.error(error);
        alert("Server error while logging in.");
      }
    });
  }
</script>
</body>
</html>