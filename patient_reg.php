<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "speech_therapy_clinic_patient");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Collecting form data
$fullname = $_POST['fullname'];
$gender = $_POST['gender'];
$contact = $_POST['contact'];
$email = $_POST['email'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];
$otp = $_POST['otp'];

// Check if passwords match
if ($password != $confirm_password) {
    die("Passwords do not match.");
}

// Check OTP functionality - this is placeholder code
if ($otp != "123456") { // Replace with actual OTP validation
    die("Invalid OTP.");
}

// Insert data into database
$sql = "INSERT INTO patients (fullname, gender, contact, email, password) VALUES ('$fullname', '$gender', '$contact', '$email', '$password')";

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully. <a href='login.html'>Login here</a>";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
