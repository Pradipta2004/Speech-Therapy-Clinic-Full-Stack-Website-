<?php
// Start session
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "", "speech_therapy_clinic_scheduled_appointment");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$therapist_name = "";
$schedules = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $therapist_name = $_POST['therapist_name'];
    
    // Fetch schedules assigned to the therapist
    $sql = "SELECT * FROM scheduled_appointments WHERE therapist_name=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $therapist_name);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $schedules[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check Schedules</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .schedule-form {
            margin-bottom: 20px;
        }
        .schedule-table {
            width: 100%;
            border-collapse: collapse;
        }
        .schedule-table th, .schedule-table td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        .schedule-table th {
            background-color: #f2f2f2;
            text-align: left;
        }
    </style>
</head>
<body>

<h2>Check Your Schedules</h2>
<form method="POST" class="schedule-form">
    <label for="therapist_name">Enter Your Name:</label>
    <input type="text" id="therapist_name" name="therapist_name" required>
    <button type="submit">Check Schedules</button>
</form>

<?php if (count($schedules) > 0): ?>
    <h3>Scheduled Appointments for <?php echo htmlspecialchars($therapist_name); ?>:</h3>
    <table class="schedule-table">
        <tr>
            <th>Patient Name</th>
            <th>Patient Phone</th>
            <th>Disease</th>
            <th>About</th>
            <th>Scheduled Date</th>
            <th>Scheduled Time</th>
        </tr>
        <?php foreach ($schedules as $schedule): ?>
            <tr>
                <td><?php echo htmlspecialchars($schedule['patient_name']); ?></td>
                <td><?php echo htmlspecialchars($schedule['patient_phone']); ?></td>
                <td><?php echo htmlspecialchars($schedule['disease']); ?></td>
                <td><?php echo htmlspecialchars($schedule['about']); ?></td>
                <td><?php echo htmlspecialchars($schedule['scheduled_date']); ?></td>
                <td><?php echo htmlspecialchars($schedule['scheduled_time']); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <?php if ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
        <p>No schedules found for <?php echo htmlspecialchars($therapist_name); ?>.</p>
    <?php endif; ?>
<?php endif; ?>

</body>
</html>
