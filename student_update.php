<?php
$conn = new mysqli('localhost', 'root', '', 'sport_event');
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kod_murid = $conn->real_escape_string($_POST['kod_murid']);
    $kedudukan = $conn->real_escape_string($_POST['kedudukan']);
    $mata = $conn->real_escape_string($_POST['mata']);
    $rekod = $conn->real_escape_string($_POST['rekod']);

    $sql = "UPDATE student_events SET kedudukan='$kedudukan', mata='$mata', rekod='$rekod' WHERE kod_murid='$kod_murid'";

    if ($conn->query($sql) === TRUE) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

$conn->close();
?>
