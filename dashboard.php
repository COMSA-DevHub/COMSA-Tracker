<?php
session_start();

// Regenerate session ID after login to prevent fixation attacks
if (!isset($_SESSION['initiated'])) {
    session_regenerate_id(true);
    $_SESSION['initiated'] = true;
}

// Check if user is logged in
if (!isset($_SESSION['student_number'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>
<h2>Welcome, <?php echo $_SESSION['name']; ?>!</h2>
<p>Email: <?php echo $_SESSION['email']; ?></p>
<p>student_number: <?php echo $_SESSION['student_number']; ?></p>

<a href="logout.php">Logout</a>
</body>
</html>
