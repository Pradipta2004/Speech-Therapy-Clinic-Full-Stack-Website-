<?php
session_start();

// Ensure the user is logged in and is a patient
if (!isset($_SESSION['userType']) || $_SESSION['userType'] != 'patient') {
    header("Location: login.html");
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "", "speech_therapy_clinic_scheduled_appointment");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize username from session
$username = $_SESSION['username'] ?? ''; // Assuming the session stores username as 'username'

// Debug statement to check if username is set correctly
if (empty($username)) {
    echo "<p>Username is not set. Please check your session configuration.</p>";
    exit();
}

// Fetch scheduled appointments for the username
$appointments = [];
$sql = "SELECT * FROM scheduled_appointments WHERE username='$username'";
$result = $conn->query($sql);

// Debug statement to check if the query executed correctly
if (!$result) {
    echo "<p>Error executing query: " . $conn->error . "</p>";
    exit();
}

if ($result->num_rows > 0) {
    $appointments = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $appointments = "No appointments found for this username.";
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Scheduled Appointments</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Your Scheduled Appointments</h1>

    <?php if (!empty($appointments) && is_array($appointments)): ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Pending Appointment ID</th>
                <th>Username</th>
                <th>Patient Name</th>
                <th>Patient Phone</th>
                <th>Preferred Date</th>
                <th>Preferred Time</th>
                <th>Disease</th>
                <th>About</th>
                <th>Therapist Name</th>
                <th>Scheduled Date</th>
                <th>Scheduled Time</th>
            </tr>
            <?php foreach ($appointments as $appointment): ?>
                <tr>
                    <td><?php echo htmlspecialchars($appointment['id']); ?></td>
                    <td><?php echo htmlspecialchars($appointment['pending_appointment_id']); ?></td>
                    <td><?php echo htmlspecialchars($appointment['username']); ?></td>
                    <td><?php echo htmlspecialchars($appointment['patient_name']); ?></td>
                    <td><?php echo htmlspecialchars($appointment['patient_phone']); ?></td>
                    <td><?php echo htmlspecialchars($appointment['preferred_date']); ?></td>
                    <td><?php echo htmlspecialchars($appointment['preferred_time']); ?></td>
                    <td><?php echo htmlspecialchars($appointment['disease']); ?></td>
                    <td><?php echo htmlspecialchars($appointment['about']); ?></td>
                    <td><?php echo htmlspecialchars($appointment['therapist_name']); ?></td>
                    <td><?php echo htmlspecialchars($appointment['scheduled_date']); ?></td>
                    <td><?php echo htmlspecialchars($appointment['scheduled_time']); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php elseif (is_string($appointments)): ?>
        <p><?php echo htmlspecialchars($appointments); ?></p>
    <?php endif; ?>
</body>
</html>
