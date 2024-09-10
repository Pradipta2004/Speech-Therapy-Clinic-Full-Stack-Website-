<?php
header('Content-Type: application/json');

// Database connection to therapist database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "speech_therapy_clinic_therapist";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(['error' => 'Connection failed: ' . $conn->connect_error]));
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['therapist_name'])) {
    $therapist_name = $conn->real_escape_string($_POST['therapist_name']);
    $sql = "SELECT * FROM therapists WHERE fullname = '$therapist_name'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $therapistData = $result->fetch_assoc();
        echo json_encode($therapistData);
    } else {
        echo json_encode(['error' => 'No therapist found with that name.']);
    }
} else {
    $sql = "SELECT fullname FROM therapists";
    $result = $conn->query($sql);

    $therapists = [];
    while ($row = $result->fetch_assoc()) {
        $therapists[] = $row;
    }
    echo json_encode($therapists);
}

$conn->close();
?>
