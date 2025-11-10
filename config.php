<?php
$servername = "localhost";
$student_number = "root";
$password = "";
$dbname = "comsa_tracker";

$conn = new mysqli($servername, $student_number, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
