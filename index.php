<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Whereabouts Employee PPD</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container">
      <a class="navbar-brand fw-bold" href="index.php">📍 Whereabouts PPD Beaufort</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <?php if (!isset($_SESSION['role'])): ?>
            <li class="nav-item">
              <a class="nav-link" href="login.php">Login</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="register.php">Register</a>
            </li>
          <?php else: ?>
            <li class="nav-item">
              <a class="nav-link" href="<?php 
                  echo ($_SESSION['role'] === 'employee') ? 'employee_dashboard.php' : 'admin_dashboard.php'; 
                ?>">Dashboard</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-warning" href="logout.php">Logout</a>
            </li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Hero Section -->
  <header class="bg-white py-5 text-center shadow-sm">
    <div class="container">
      <h1 class="fw-bold">Welcome to the Whereabouts Employee System</h1>
      <p class="lead text-muted">Easily track employee attendance, locations, and leave status for PPD Beaufort.</p>
      <?php if (!isset($_SESSION['role'])): ?>
        <a href="login.php" class="btn btn-primary btn-lg me-2">Login</a>
        <a href="register.php" class="btn btn-outline-primary btn-lg">Register</a>
      <?php else: ?>
        <a href="<?php echo ($_SESSION['role'] === 'employee') ? 'employee_dashboard.php' : 'admin_dashboard.php'; ?>" 
           class="btn btn-success btn-lg">Go to Dashboard</a>
      <?php endif; ?>
    </div>
  </header>

  <!-- Info Cards -->
  <section class="container my-5">
    <div class="row text-center">
      <div class="col-md-4 mb-3">
        <div class="card shadow-sm p-4 h-100">
          <h5 class="fw-bold">👨‍💼 Employees</h5>
          <p class="text-muted">Log attendance, mark location (office, outside, leave), and add justifications.</p>
        </div>
      </div>
      <div class="col-md-4 mb-3">
        <div class="card shadow-sm p-4 h-100">
          <h5 class="fw-bold">🛡️ Admins</h5>
          <p class="text-muted">Manage employees, view reports, and monitor department activities.</p>
        </div>
      </div>
      <div class="col-md-4 mb-3">
        <div class="card shadow-sm p-4 h-100">
          <h5 class="fw-bold">⭐ Super Admin</h5>
          <p class="text-muted">Highest privilege. Can manage admins, reset accounts, and oversee the entire system.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- News & Announcements -->
  <section class="container my-5">
    <h3 class="fw-bold text-center mb-4">📰 News & Announcements</h3>
    <div class="row">
      <!-- Example Announcement 1 -->
      <div class="col-md-6 mb-3">
        <div class="card shadow-sm h-100">
          <div class="card-body">
            <h5 class="card-title">System Launch</h5>
            <p class="card-text text-muted">The Whereabouts Employee System is now live! All employees are required to register and log their attendance daily.</p>
            <small class="text-secondary">Posted: <?php echo date("d M Y"); ?></small>
          </div>
        </div>
      </div>

      <!-- Example Announcement 2 -->
      <div class="col-md-6 mb-3">
        <div class="card shadow-sm h-100">
          <div class="card-body">
            <h5 class="card-title">Reminder</h5>
            <p class="card-text text-muted">Please ensure that you submit your whereabouts before 9:00 AM every working day.</p>
            <small class="text-secondary">Posted: <?php echo date("d M Y", strtotime("-1 day")); ?></small>
          </div>
        </div>
      </div>
    </div>
    <p class="text-center mt-3 text-muted"><em>(More announcements will appear here once added by Admins)</em></p>
  </section>

  <!-- Footer -->
  <footer class="bg-primary text-white text-center py-3 mt-5">
    <div class="container">
      <small>&copy; <?php echo date("Y"); ?> Whereabouts Employee PPD. All Rights Reserved.</small>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
