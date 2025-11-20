<?php
include '../functions/config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Delete related rows first
    $conn->query("DELETE FROM events_printed WHERE event_id='$id'");
    $conn->query("DELETE FROM events_signed WHERE event_id='$id'");
    // Delete the event
    $conn->query("DELETE FROM events WHERE id='$id'");

    header("Location: ../admin/events.php");
}
?>
