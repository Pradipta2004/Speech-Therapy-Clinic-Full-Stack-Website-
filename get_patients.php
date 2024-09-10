<?php
$conn = new mysqli("localhost", "root", "", "speech_therapy_clinic_patient");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT DISTINCT patient_name FROM patient_data"; // Change to your table and column name
$result = $conn->query($sql);

$patients = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $patients[] = $row['patient_name'];
    }
}

header('Content-Type: application/json');
echo json_encode($patients);

$conn->close();
?>
