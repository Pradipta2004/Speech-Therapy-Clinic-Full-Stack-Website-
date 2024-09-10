<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "speech_therapy_clinic_therapist");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = $_GET['id'];

// Delete therapist from therapists
$sql = "DELETE FROM therapists WHERE id='$id'";

if ($conn->query($sql) === TRUE) {
    echo "Therapist rejected successfully.";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
