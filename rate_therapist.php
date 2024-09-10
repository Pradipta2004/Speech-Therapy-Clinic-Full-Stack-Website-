<?php
// rate_therapist.php
session_start();
if ($_SESSION['userType'] != 'supervisor') {
    header("Location: login.html");
    exit();
}

$conn = new mysqli("localhost", "root", "", "speech_therapy_clinic_approved");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$therapist_name = $conn->real_escape_string($_POST['therapist_name']);
$rating = (int)$_POST['rating'];

if ($rating < 1 || $rating > 5) {
    die("Invalid rating. Rating must be between 1 and 5.");
}

$sql = "UPDATE approved_therapists SET rating=? WHERE fullname=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $rating, $therapist_name);

if ($stmt->execute()) {
    echo "Rating updated successfully.";
} else {
    echo "Error updating rating: " . $stmt->error;
}

$stmt->close();
$conn->close();

// Redirect back to supervisor dashboard
header("Location: supervisor_dashboard.php");
exit();
