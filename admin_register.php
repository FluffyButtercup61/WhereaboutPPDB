<?php
session_start();
include("db.php");

// ✅ Restrict access: only logged-in admins can register new admins
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$success = $error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (!empty($name) && !empty($username) && !empty($password)) {
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert into Admin table
        $stmt = $conn->prepare("INSERT INTO Admin (name, username, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $username, $hashed_password);

        if ($stmt->execute()) {
            echo "<script>
                    alert('New admin registered successfully!');
                    window.location.href = 'admin_dashboard.php';
                  </script>";
            exit();
        } else {
            $error = "Username already exists!";
        }
    } else {
        $error = "All fields are required!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Register New Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card shadow p-4" style="max-width: 450px; width: 100%;">
      <h3 class="text-center mb-4">Register New Admin</h3>
      <?php if ($error): ?>
        <div class="alert alert-danger text-center"><?php echo $error; ?></div>
      <?php endif; ?>
      <form method="POST" action="">
        <div class="mb-3">
          <label class="form-label">Full Name</label>
          <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Username</label>
          <input type="text" name="username" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Password</label>
          <input type="password" name="password" class="form-control" required>
        </div>
        <div class="d-grid">
          <button type="submit" class="btn btn-success">Create Admin</button>
        </div>
      </form>
      <div class="text-center mt-3">
        <a href="admin_dashboard.php" class="btn btn-secondary btn-sm">Back to Dashboard</a>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
