<?php
include '../functions/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $type = $_POST['type'];
    $title = $_POST['title'];
    $due_date = $_POST['due_date'];
    $status = $_POST['status'];

    // Insert into events table
    $conn->query("INSERT INTO events (type, title, due_date, status) VALUES ('$type','$title','$due_date','$status')");
    $event_id = $conn->insert_id;

    // Insert empty rows for Printed (specify columns except id)
    $conn->query("INSERT INTO events_printed (event_id, sas_f6, transmittal, invitation, endorsement) 
                  VALUES ('$event_id', '', '', '', '')");

    // Insert empty rows for Signed (specify columns except id)
    $conn->query("INSERT INTO events_signed (event_id, sas_f6, transmittal, invitation, endorsement) 
                  VALUES ('$event_id', '', '', '', '')");

    header("Location: ../admin/events.php");
}
?>
