<?php
include('functions/config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $student_number = $_POST['student_number'];
    $role = $_POST['role'];
    $type = $_POST['type'];
    $is_admin = isset($_POST['is_admin']) ? 1 : 0;
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (name, email, student_number, password, role, type, is_admin)
            VALUES ('$name', '$email', '$student_number', '$password', '$role', '$type', '$is_admin')";
    
    if ($conn->query($sql) === TRUE) {
        echo "Registration successful! <a href='login.php'>Login here</a>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Register | COMSA-TRACKER</title>
  <!-- Bootstrap 5 CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="styles.css">
</head>
<body class="bg-light d-flex align-items-center justify-content-center vh-100">

  <div class="card shadow-lg border-0 p-4" style="width: 100%; max-width: 450px; border-radius: 16px;">
    <div class="card-body">
      <h3 class="mb-4">Register</h3>
      
      <form method="POST" action="">
        <div class="mb-3">
          <label for="name" class="form-label">Full Name (SURNAME, First Name Middle initial)</label>
          <input type="text" class="form-control" id="name" name="name" required placeholder="Enter full name">
        </div>

        <div class="mb-3">
          <label for="email" class="form-label">Email</label>
          <input type="email" class="form-control" id="email" name="email" required placeholder="Enter email">
        </div>

        <div class="mb-3">
          <label for="student_number" class="form-label">Student Number</label>
          <input type="text" class="form-control" id="student_number" name="student_number" required placeholder="Enter student number">
        </div>

        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input type="password" class="form-control" id="password" name="password" required placeholder="Enter password">
        </div>

        <div class="mb-3">
          <label for="role" class="form-label">Role</label>
          <select class="form-select" id="role" name="role" required>
            <option value="">Select Role</option>
            <option value="Committee Member">Committee Member</option>
            <option value="Committee Head">Committee Head</option>
            <option value="Executive Officer">Executive Officer</option>
            <option value="Representative">Representative</option>
          </select>
        </div>

        <div class="mb-3">
          <label for="type" class="form-label">Type (Position / Committee Line)</label>
          <input type="text" class="form-control" id="type" name="type" placeholder="e.g. President, CSIT">
        </div>

        <button type="submit" class="btn btn-comsa w-100 py-2">Register</button>
      </form>

      <div class="text-center mt-3">
        <p class="mb-0">Already have an account? <a href="login.php" class="text-decoration-none comsa-text">Login Here</a></p>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
