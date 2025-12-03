<?php
// index.php

// Check if the user is already logged in (optional, but good practice)
session_start();
if (isset($_SESSION['user_id'])) {
    // If logged in, redirect to their respective dashboard/task page
    if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']) {
        header("Location: admin/admin_dashboard.php");
    } else {
        header("Location: user_tasks.php");
    }
    exit();
} else {
    // If not logged in, force redirect to the login page
    header("Location: login.php");
    exit();
}
?>