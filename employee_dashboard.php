<?php
session_start();
include("db.php");

// Redirect if not logged in
if (!isset($_SESSION['employee_id'])) {
    header("Location: login.php");
    exit();
}

$employee_id = $_SESSION['employee_id'];
$employee_name = $_SESSION['employee_name'];
$message = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $department = $_POST['department'];
    $status = $_POST['status'];
    $justification = trim($_POST['justification']);
    $attendance_date = date("Y-m-d H:i:s");

    $stmt = $conn->prepare("INSERT INTO Whereabouts (employee_id, department, attendance_date, status, justification) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $employee_id, $department, $attendance_date, $status, $justification);

    if ($stmt->execute()) {
        $message = "✅ Whereabouts logged successfully!";
    } else {
        $message = "❌ Error: Could not save record.";
    }
}

// Fetch employee whereabouts history
$stmt = $conn->prepare("SELECT department, attendance_date, status, justification 
                        FROM Whereabouts 
                        WHERE employee_id = ? 
                        ORDER BY attendance_date DESC");
$stmt->bind_param("i", $employee_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Employee Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container my-5">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="fw-bold text-primary">Welcome, <?php echo htmlspecialchars($employee_name); ?></h2>
      <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>

    <!-- Alert Message -->
    <?php if ($message): ?>
      <div class="alert alert-info alert-dismissible fade show" role="alert">
        <?php echo $message; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php endif; ?>

    <!-- Whereabouts Form -->
    <div class="card shadow-sm mb-4">
      <div class="card-header bg-success text-white">
        <h5 class="mb-0">Log Your Whereabouts</h5>
      </div>
      <div class="card-body">
        <form method="POST" action="">
          <div class="mb-3">
            <label class="form-label">Department</label>
            <select name="department" class="form-select" required>
              <option value="">-- Select Department --</option>
              <option value="PPD">PPD</option>
              <option value="SPr">SPr</option>
              <option value="SPb">SPb</option>
              <option value="SPS">SPS</option>
              <option value="SPM">SPM</option>
              <option value="SPsK">SPsK</option>
              <option value="SPP">SPP</option>
              <option value="SP">SP</option>
              <option value="PTIS">PTIS</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select" required>
              <option value="">-- Select Status --</option>
              <option value="in_office">In Office</option>
              <option value="outside">Working Outside</option>
              <option value="on_leave">On Leave</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Justification</label>
            <textarea name="justification" class="form-control" rows="3" placeholder="Provide details..." required></textarea>
          </div>
          <button type="submit" class="btn btn-success ">Submit</button>
        </form>
      </div>
    </div>

    <!-- Whereabouts History -->
    <div class="card shadow-sm">
      <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Your Whereabouts History</h5>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover align-middle">
            <thead class="table-dark">
              <tr>
                <th>Date & Time</th>
                <th>Department</th>
                <th>Status</th>
                <th>Justification</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                  <td><?php echo date("d M Y, H:i", strtotime($row['attendance_date'])); ?></td>
                  <td><?php echo $row['department']; ?></td>
                  <td>
                    <?php 
                      if ($row['status'] == 'in_office') {
                          echo '<span class="badge bg-success">In Office</span>';
                      } elseif ($row['status'] == 'outside') {
                          echo '<span class="badge bg-warning text-dark">Working Outside</span>';
                      } else {
                          echo '<span class="badge bg-danger">On Leave</span>';
                      }
                    ?>
                  </td>
                  <td><?php echo htmlspecialchars($row['justification']); ?></td>
                </tr>
              <?php endwhile; ?>
              <?php if ($result->num_rows === 0): ?>
                <tr>
                  <td colspan="4" class="text-center text-muted">No records found.</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
