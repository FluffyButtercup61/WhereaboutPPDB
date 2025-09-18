<?php
session_start();
include("db.php");

// Redirect if not admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$admin_name = $_SESSION['admin_name'];

// Default date = today
$selected_date = isset($_GET['date']) ? $_GET['date'] : date("Y-m-d");

// === 1. Dashboard Summary: total by department + status ===
$summary_query = "
    SELECT department, status, COUNT(*) as total
    FROM Whereabouts
    WHERE DATE(attendance_date) = ?
    GROUP BY department, status
";
$stmt = $conn->prepare($summary_query);
$stmt->bind_param("s", $selected_date);
$stmt->execute();
$summary_result = $stmt->get_result();

$summary = [];
while ($row = $summary_result->fetch_assoc()) {
    $summary[$row['department']][$row['status']] = $row['total'];
}

// === 2. Detailed Employee List by Date ===
$detail_query = "
    SELECT e.name, w.department, w.status, w.justification, w.attendance_date
    FROM Whereabouts w
    JOIN Employee e ON w.employee_id = e.id
    WHERE DATE(w.attendance_date) = ?
    ORDER BY w.department, w.status, e.name
";
$stmt = $conn->prepare($detail_query);
$stmt->bind_param("s", $selected_date);
$stmt->execute();
$detail_result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Dashboard - Whereabouts System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2>Admin Dashboard</h2>
      <div>
        <span class="me-3">Welcome, <b><?php echo htmlspecialchars($admin_name); ?></b></span>
        <a href="logout.php" class="btn btn-danger">Logout</a>
      </div>
    </div>

    <!-- Date Filter -->
    <form method="GET" class="mb-4 d-flex align-items-center">
      <label class="me-2 fw-bold">Select Date:</label>
      <input type="date" name="date" value="<?php echo $selected_date; ?>" class="form-control me-2" style="max-width:200px;">
      <button type="submit" class="btn btn-primary">Filter</button>
    </form>

    <!-- 1. Dashboard Summary -->
    <div class="card shadow p-4 mb-4">
      <h4 class="mb-3">Summary by Department (<?php echo $selected_date; ?>)</h4>
      <div class="table-responsive">
        <table class="table table-bordered table-striped text-center">
          <thead class="table-dark">
            <tr>
              <th>Department</th>
              <th>In Office</th>
              <th>Working Outside</th>
              <th>On Leave</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $departments = ['PPD','SPr','SPb','SPS','SPM','SPsK','SPP','SP','PTIS'];
            foreach ($departments as $dept):
              $in_office = $summary[$dept]['in_office'] ?? 0;
              $outside   = $summary[$dept]['outside'] ?? 0;
              $on_leave  = $summary[$dept]['on_leave'] ?? 0;
            ?>
            <tr>
              <td><?php echo $dept; ?></td>
              <td><?php echo $in_office; ?></td>
              <td><?php echo $outside; ?></td>
              <td><?php echo $on_leave; ?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- 2. Detailed List -->
    <div class="card shadow p-4">
      <h4 class="mb-3">Detailed Employee Whereabouts (<?php echo $selected_date; ?>)</h4>
      <div class="table-responsive">
        <table class="table table-hover table-striped">
          <thead class="table-dark">
            <tr>
              <th>Name</th>
              <th>Department</th>
              <th>Status</th>
              <th>Justification</th>
              <th>Time</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = $detail_result->fetch_assoc()): ?>
            <tr>
              <td><?php echo htmlspecialchars($row['name']); ?></td>
              <td><?php echo $row['department']; ?></td>
              <td>
                <?php 
                  if ($row['status'] == 'in_office') echo "In Office";
                  elseif ($row['status'] == 'outside') echo "Working Outside";
                  else echo "On Leave";
                ?>
              </td>
              <td><?php echo htmlspecialchars($row['justification']); ?></td>
              <td><?php echo date("H:i", strtotime($row['attendance_date'])); ?></td>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>

  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
