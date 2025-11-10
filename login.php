<?php
include('config.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_number = htmlspecialchars(trim($_POST['student_number']));
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE student_number = ?");
    $stmt->bind_param("s", $student_number);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['student_number'] = $user['student_number'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['type'] = $user['type'];
            $_SESSION['is_admin'] = $user['is_admin'];
            $_SESSION['initiated'] = true;

            if ($user['is_admin']) {
                header("Location: admin_dashboard.php");
            } else {
                header("Location: dashboard.php");
            }
            exit();
        }
    }

    echo "Invalid student_number or password.";
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login | COMSA - TRACKER</title>
  <!-- Bootstrap 5 CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="styles.css">
</head>
<body class="bg-light d-flex align-items-center justify-content-center vh-100">

  <div class="card shadow-lg border-0 p-4" style="width: 100%; max-width: 400px; border-radius: 16px;">
    <div class="card-body">
    <div class="text-center mb-4">
        <img src="img/secondary_logo.png" alt="COMSA Logo" style="width: 200px;">
    </div>
      <form method="POST" action="">
        <div class="mb-3">
          <label for="student_number" class="form-label">Student Number</label>
          <input type="text" class="form-control" id="student_number" name="student_number" required placeholder="Enter your student number">
        </div>

        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input type="password" class="form-control" id="password" name="password" required placeholder="Enter your password">
        </div>

        <button type="submit" class="btn btn-comsa w-100 py-2">Login</button>
      </form>
        
      <div class="text-center mt-3">
       <p class="mb-0">Forgot your password? <a href="forgot_password.php" class="text-decoration-none comsa-text">Click Here</a></p>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS (optional for animations, alerts, etc.) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
