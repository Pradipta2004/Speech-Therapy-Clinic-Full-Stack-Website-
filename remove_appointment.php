<?php
session_start();

if ($_SESSION['userType'] != 'patient') {
    header("Location: login.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['appointment_id'])) {
    $appointment_id = intval($_POST['appointment_id']);
    $conn_appointment = new mysqli("localhost", "root", "", "speech_therapy_clinic_scheduled_appointment");

    if ($conn_appointment->connect_error) {
        die("Connection failed: " . $conn_appointment->connect_error);
    }

    // Optionally, you can add a query to update the status of the appointment if needed
    // $sql_update = "UPDATE scheduled_appointments SET status='hidden' WHERE id='$appointment_id'";
    // $conn_appointment->query($sql_update);

    $conn_appointment->close();
}
?>
