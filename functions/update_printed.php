<?php
include '../functions/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    
    // Checkboxes only send a value if they are checked.
    // Set variable to 1 if sent (checked), 0 if not sent (unchecked).
    $sas_f6 = isset($_POST['sas_f6']) ? 1 : 0;
    $transmittal = isset($_POST['transmittal']) ? 1 : 0;
    $invitation = isset($_POST['invitation']) ? 1 : 0;
    $endorsement = isset($_POST['endorsement']) ? 1 : 0;

    // The column name for the Event ID in events_printed is likely 'event_id', not 'id'.
    // Your main fetch query used 'e.id = ep.event_id', so we must use 'event_id' here.
    
    // Use Prepared Statement for security and use the correct column names for the SET clause.
    $stmt = $conn->prepare("
        UPDATE events_printed 
        SET sas_f6=?, transmittal=?, invitation=?, endorsement=? 
        WHERE event_id=?
    ");

    // Bind parameters: i for integer (for all 5 variables)
    $stmt->bind_param("iiiii", $sas_f6, $transmittal, $invitation, $endorsement, $id);

    if ($stmt->execute()) {
        $stmt->close();
        header("Location: ../admin/events.php");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>