<?php
session_start();
if ($_SESSION['userType'] != 'supervisor') {
    header("Location: login.html");
    exit();
}

// Database connection to the approved therapists database
$conn = new mysqli("localhost", "root", "", "speech_therapy_clinic_approved");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve and sanitize form inputs
$therapist_id = $conn->real_escape_string($_POST['therapist_id']);
$rating = $conn->real_escape_string($_POST['rating']);

// Update the rating in the database
$sql = "UPDATE approved_therapists SET rating = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ii', $rating, $therapist_id);

if ($stmt->execute()) {
    echo "Rating updated successfully.";
} else {
    echo "Error updating rating: " . $stmt->error;
}

// Close the connection
$stmt->close();
$conn->close();

// Redirect back to the supervisor dashboard
header("Location: supervisor_dashboard.php");
exit();
?>
