<?php
// Database connection to therapist database
$therapist_conn = new mysqli("localhost", "root", "", "speech_therapy_clinic_therapist");

if ($therapist_conn->connect_error) {
    die("Connection failed: " . $therapist_conn->connect_error);
}

// Get therapist ID from POST data
$therapist_id = $_POST['id'];

// Fetch therapist data
$sql = "SELECT * FROM therapists WHERE id='$therapist_id'";
$result = $therapist_conn->query($sql);

if ($result->num_rows == 1) {
    $therapist = $result->fetch_assoc();

    // Database connection to the new database
    $approved_conn = new mysqli("localhost", "root", "", "speech_therapy_clinic_approved");

    if ($approved_conn->connect_error) {
        // Create the new database if it doesn't exist
        $conn = new mysqli("localhost", "root", "");
        $conn->query("CREATE DATABASE speech_therapy_clinic_approved");
        $conn->close();

        $approved_conn = new mysqli("localhost", "root", "", "speech_therapy_clinic_approved");

        // Create the approved_therapists table
        $createTable = "CREATE TABLE approved_therapists (
            id INT AUTO_INCREMENT PRIMARY KEY,
            fullname VARCHAR(100),
            dob DATE,
            gender VARCHAR(10),
            contact VARCHAR(15),
            license VARCHAR(50),
            education VARCHAR(100),
            experience VARCHAR(50),
            specialization VARCHAR(100),
            certificate VARCHAR(255),
            photo VARCHAR(255),
            email VARCHAR(100) UNIQUE,
            password VARCHAR(255)
        )";

        if (!$approved_conn->query($createTable)) {
            die("Error creating table: " . $approved_conn->error);
        }
    }

    // Insert into the new database
    $sql = "INSERT INTO approved_therapists (fullname, dob, gender, contact, license, education, experience, specialization, certificate, photo, email, password)
            VALUES ('" . $therapist['fullname'] . "', '" . $therapist['dob'] . "', '" . $therapist['gender'] . "', '" . $therapist['contact'] . "', '" . $therapist['license'] . "', '" . $therapist['education'] . "', '" . $therapist['experience'] . "', '" . $therapist['specialization'] . "', '" . $therapist['certificate'] . "', '" . $therapist['photo'] . "', '" . $therapist['email'] . "', '" . $therapist['password'] . "')";

    if ($approved_conn->query($sql) === TRUE) {
        // Remove from the original database
        $sql = "DELETE FROM therapists WHERE id='$therapist_id'";
        $therapist_conn->query($sql);
        echo "Therapist approved successfully!";
    } else {
        echo "Error: " . $approved_conn->error;
    }

    $approved_conn->close();
} else {
    echo "Therapist not found.";
}

$therapist_conn->close();
?>
