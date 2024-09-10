<?php
session_start();

if ($_SESSION['userType'] != 'therapist') {
    header("Location: login.html");
    exit();
}

$therapistName = $_SESSION['fullname']; // Assuming therapist's fullname is stored in session
$patientName = $_POST['patient_name'];
$message = $_POST['message'];

// Database connection
$conn = new mysqli("localhost", "root", "", "speech_therapy_clinic_chat");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$stmt = $conn->prepare("INSERT INTO messages (therapist_name, patient_name, message) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $therapistName, $patientName, $message);

if ($stmt->execute()) {
    echo "Message sent successfully.";
} else {
    echo "Error sending message: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
