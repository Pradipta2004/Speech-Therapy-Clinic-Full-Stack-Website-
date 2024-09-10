<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "speech_therapy_clinic_scheduled_appointment");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
$appointments = [];
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['username'])) {
    $username = $conn->real_escape_string($_POST['username']);

    // Query to fetch scheduled appointments for the username
    $sql = "SELECT * FROM scheduled_appointments WHERE username LIKE '%$username%'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $appointments = $result->fetch_all(MYSQLI_ASSOC);
    } else {
        $appointments = "No appointments found for this username.";
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find Appointments</title>
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
        input[type="text"], input[type="submit"] {
            padding: 10px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <h1>Find Patient Appointments</h1>
    <form method="POST">
        <label for="username">Enter Username:</label>
        <input type="text" id="username" name="username" required>
        <input type="submit" value="Search">
    </form>

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
