<?php
include '../functions/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $type = $_POST['type'];
    $title = $_POST['title'];
    $due_date = $_POST['due_date'];
    $status = $_POST['status'];

    $conn->query("UPDATE events SET type='$type', title='$title', due_date='$due_date', status='$status' WHERE id='$id'");
    header("Location: ../admin/events.php");
}
?>
