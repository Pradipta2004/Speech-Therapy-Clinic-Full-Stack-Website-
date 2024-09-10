<?php
// Create the database for scheduled appointments if it doesn't exist
$createDbSql = "CREATE DATABASE IF NOT EXISTS speech_therapy_clinic_scheduled_appointment";
$conn = new mysqli("localhost", "root", "", "");
if ($conn->query($createDbSql) === FALSE) {
    die("Error creating database: " . $conn->error);
}
$conn->close();

// Connect to the scheduled appointments database
$conn = new mysqli("localhost", "root", "", "speech_therapy_clinic_scheduled_appointment");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create the scheduled_appointments table if it doesn't exist
$createTableSql = "CREATE TABLE IF NOT EXISTS scheduled_appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pending_appointment_id INT NOT NULL,
    username VARCHAR(100),
    patient_name VARCHAR(100),
    patient_phone VARCHAR(15),
    preferred_date DATE,
    preferred_time TIME,
    disease VARCHAR(255),
    about TEXT,
    therapist_name VARCHAR(100),
    scheduled_date DATE,
    scheduled_time TIME,
    FOREIGN KEY (pending_appointment_id) REFERENCES speech_therapy_clinic_pending_appointment.pending_appointment(id) ON DELETE CASCADE
)";

if ($conn->query($createTableSql) === FALSE) {
    die("Error creating table: " . $conn->error);
}

$pending_appointment_id = $_POST['pending_appointment_id'];
$scheduled_date = $_POST['scheduled_date'];
$scheduled_time = $_POST['scheduled_time'];

// Retrieve pending appointment details
$pending_conn = new mysqli("localhost", "root", "", "speech_therapy_clinic_pending_appointment");
$sql = "SELECT * FROM pending_appointment WHERE id='$pending_appointment_id'";
$result = $pending_conn->query($sql);
$appointment = $result->fetch_assoc();

$username = $appointment['username'];
$patient_name = $appointment['patient_name'];
$patient_phone = $appointment['patient_phone'];
$preferred_date = $appointment['preferred_date'];
$preferred_time = $appointment['preferred_time'];
$disease = $appointment['disease'];
$about = $appointment['about'];
$therapist_name = $appointment['therapist_name'];

// Insert into scheduled_appointments table
$sql = "INSERT INTO scheduled_appointments (pending_appointment_id, username, patient_name, patient_phone, preferred_date, preferred_time, disease, about, therapist_name, scheduled_date, scheduled_time)
        VALUES ('$pending_appointment_id', '$username', '$patient_name', '$patient_phone', '$preferred_date', '$preferred_time', '$disease', '$about', '$therapist_name', '$scheduled_date', '$scheduled_time')";

if ($conn->query($sql) === TRUE) {
    echo "Appointment scheduled successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
$pending_conn->close();
?>
