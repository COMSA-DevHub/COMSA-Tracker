<?php
include('functions/config.php');
session_start();

$error_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 1. Input Validation
    if (empty($_POST['student_number']) || empty($_POST['password'])) {
        $error_message = "Please enter both student number and password.";
    } else {
        $student_number = htmlspecialchars(trim($_POST['student_number']));
        $password = $_POST['password'];

        // 2. Fetch user data
        $stmt = $conn->prepare("SELECT id, student_number, name, email, role, type, is_admin, password FROM users WHERE student_number = ?");
        
        if ($stmt === false) {
            // Database preparation error (rare, but good to handle)
            $error_message = "A system error occurred. Please try again later. (SQL Prepare)";
        } else {
            $stmt->bind_param("s", $student_number);
            
            if ($stmt->execute()) {
                $result = $stmt->get_result();

                if ($result->num_rows === 1) {
                    $user = $result->fetch_assoc();
                    
                    // 3. Password Verification
                    if (password_verify($password, $user['password'])) {
                        // Success: Start session and redirect
                        session_regenerate_id(true);
                        
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['student_number'] = $user['student_number'];
                        $_SESSION['name'] = $user['name'];
                        $_SESSION['email'] = $user['email'];
                        $_SESSION['role'] = $user['role'];
                        $_SESSION['type'] = $user['type'];
                        $_SESSION['is_admin'] = $user['is_admin'];
                        $_SESSION['initiated'] = true;

                        if ($user['is_admin']) {
                            header("Location: admin/admin_dashboard.php");
                        } else {
                            header("Location: user_tasks.php");
                        }
                        exit();
                    }
                }
                
                // 4. Failed Login (Handle incorrect credentials securely)
                $error_message = "Invalid student number or password.";
                
            } else {
                // Database execution error
                $error_message = "A system error occurred. Please try again later. (SQL Execute)";
            }
            $stmt->close();
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | COMSA - TRACKER</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body class="bg-light d-flex align-items-center justify-content-center vh-100">

    <div class="card shadow-lg border-0 p-4" style="width: 100%; max-width: 400px; border-radius: 16px;">
        <div class="card-body">
            <div class="text-center mb-4">
                <img src="img/tracker-logo2.png" alt="COMSA Logo" style="width: 200px;">
            </div>
            
            <?php 
            // Display error message with enhanced layout
            if (!empty($error_message)) {
                echo '
                <div class="alert alert-danger d-flex align-items-center mb-4" role="alert">
                    <i class="ri-alert-fill me-2 fs-5"></i> 
                    <div>
                        ' . htmlspecialchars($error_message) . '
                    </div>
                </div>
                ';
            }
            ?>
            
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>