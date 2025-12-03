<?php
// functions/delete_task.php

session_start();
require_once "config.php";

$redirect_page = "../admin/tasks.php"; // Assuming this is the redirect destination

// 1. SECURITY & METHOD CHECK: Ensure POST request and the 'id' field exists.
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    
    // Sanitize and validate the ID
    // PHP will now look for $_POST['id']
    $task_id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT); 
    
    if ($task_id) {
        
        $sql_delete = "DELETE FROM tasks WHERE id = ?";
        
        if ($stmt = $conn->prepare($sql_delete)) {
            $stmt->bind_param("i", $task_id);
            
            if ($stmt->execute()) {
                $_SESSION['success_message'] = "Task ID {$task_id} deleted successfully.";
            } else {
                $_SESSION['error_message'] = "Error deleting task: " . $stmt->error;
            }

            $stmt->close();
        } else {
            $_SESSION['error_message'] = "Database error preparing deletion statement.";
        }

    } else {
        $_SESSION['error_message'] = "Invalid or missing Task ID for deletion.";
    }

    $conn->close();

    // 4. REDIRECT
    header("Location: " . $redirect_page);
    exit();

} else {
    // If not POST or missing ID, redirect.
    header("Location: " . $redirect_page);
    exit();
}
?>