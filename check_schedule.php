<?php
if (!isset($_GET['therapist_name'])) {
    die("No therapist specified.");
}

$therapist_name = $_GET['therapist_name'];

// Database connection
$conn = new mysqli("localhost", "root", "", "speech_therapy_clinic_scheduled_appointment");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Creating the database for storing reports (if not exists)
$create_db_sql = "CREATE TABLE IF NOT EXISTS therapist_reports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    therapist_name VARCHAR(255) NOT NULL,
    patient_name VARCHAR(255) NOT NULL,
    progress_report_period VARCHAR(255),
    date_report_written DATE,
    diagnoses TEXT,
    previous_level_of_functioning TEXT,
    current_level_of_functioning TEXT,
    prognosis TEXT,
    plan_of_care_patient_goals TEXT,
    plan_of_care_therapeutic_intervention TEXT,
    goals TEXT,
    recommendations TEXT,
    link_of_prescription TEXT
)";
$conn->query($create_db_sql);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_name = $_POST['patient_name'];
    $progress_report_period = $_POST['progress_report_period'];
    $date_report_written = $_POST['date_report_written'];
    $diagnoses = $_POST['diagnoses'];
    $previous_level_of_functioning = $_POST['previous_level_of_functioning'];
    $current_level_of_functioning = $_POST['current_level_of_functioning'];
    $prognosis = $_POST['prognosis'];
    $plan_of_care_patient_goals = $_POST['plan_of_care_patient_goals'];
    $plan_of_care_therapeutic_intervention = $_POST['plan_of_care_therapeutic_intervention'];
    $goals = $_POST['goals'];
    $recommendations = $_POST['recommendations'];
    $link_of_prescription = $_POST['link_of_prescription'];

    $insert_sql = "INSERT INTO therapist_reports (therapist_name, patient_name, progress_report_period, date_report_written, diagnoses, previous_level_of_functioning, current_level_of_functioning, prognosis, plan_of_care_patient_goals, plan_of_care_therapeutic_intervention, goals, recommendations, link_of_prescription) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_sql);
    $stmt->bind_param("sssssssssssss", $therapist_name, $patient_name, $progress_report_period, $date_report_written, $diagnoses, $previous_level_of_functioning, $current_level_of_functioning, $prognosis, $plan_of_care_patient_goals, $plan_of_care_therapeutic_intervention, $goals, $recommendations, $link_of_prescription);
    $stmt->execute();

    echo "Report successfully submitted!";
    $stmt->close();
}

// Fetch the schedules for the specific therapist
$sql = "SELECT * FROM scheduled_appointments WHERE therapist_name=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $therapist_name);
$stmt->execute();
$result = $stmt->get_result();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Schedules</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        .report-button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
    <script>
        function showModal(patientName) {
            document.getElementById("patient_name").value = patientName;
            document.getElementById("reportModal").style.display = "block";
        }
        function closeModal() {
            document.getElementById("reportModal").style.display = "none";
        }
        window.onclick = function(event) {
            if (event.target == document.getElementById("reportModal")) {
                document.getElementById("reportModal").style.display = "none";
            }
        }
    </script>
</head>
<body>
    <h2>Schedules for <?php echo htmlspecialchars($therapist_name); ?></h2>
    <?php
    if ($result->num_rows > 0) {
        echo "<table border='1'>";
        echo "<tr><th>Patient Name</th><th>Phone</th><th>Preferred Date</th><th>Preferred Time</th><th>Disease</th><th>About</th><th>Scheduled Date</th><th>Scheduled Time</th><th>Actions</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['patient_name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['patient_phone']) . "</td>";
            echo "<td>" . htmlspecialchars($row['preferred_date']) . "</td>";
            echo "<td>" . htmlspecialchars($row['preferred_time']) . "</td>";
            echo "<td>" . htmlspecialchars($row['disease']) . "</td>";
            echo "<td>" . htmlspecialchars($row['about']) . "</td>";
            echo "<td>" . htmlspecialchars($row['scheduled_date']) . "</td>";
            echo "<td>" . htmlspecialchars($row['scheduled_time']) . "</td>";
            echo "<td><button class='report-button' onclick='showModal(\"" . htmlspecialchars($row['patient_name']) . "\")'>Create Report</button></td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "No schedules found for this therapist.";
    }

    $stmt->close();
    $conn->close();
    ?>
    
    <!-- The Modal -->
    <div id="reportModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Create Report</h2>
            <form method="POST">
                <input type="hidden" id="patient_name" name="patient_name" value="">
                <label for="progress_report_period">Progress Report Period:</label>
                <input type="text" id="progress_report_period" name="progress_report_period" required><br><br>
                <label for="date_report_written">Date Report Was Written:</label>
                <input type="date" id="date_report_written" name="date_report_written" required><br><br>
                <label for="diagnoses">Diagnoses:</label>
                <textarea id="diagnoses" name="diagnoses" required></textarea><br><br>
                <label for="previous_level_of_functioning">Previous Level of Functioning:</label>
                <textarea id="previous_level_of_functioning" name="previous_level_of_functioning" required></textarea><br><br>
                <label for="current_level_of_functioning">Current Level of Functioning:</label>
                <textarea id="current_level_of_functioning" name="current_level_of_functioning" required></textarea><br><br>
                <label for="prognosis">Prognosis:</label>
                <textarea id="prognosis" name="prognosis" required></textarea><br><br>
                <label for="plan_of_care_patient_goals">Plan of Care: Patient Goals:</label>
                <textarea id="plan_of_care_patient_goals" name="plan_of_care_patient_goals" required></textarea><br><br>
                <label for="plan_of_care_therapeutic_intervention">Plan of Care: Therapeutic Intervention:</label>
                <textarea id="plan_of_care_therapeutic_intervention" name="plan_of_care_therapeutic_intervention" required></textarea><br><br>
                <label for="goals">Goals:</label>
                <textarea id="goals" name="goals" required></textarea><br><br>
                <label for="recommendations">Recommendations:</label>
                <textarea id="recommendations" name="recommendations" required></textarea><br><br>
                <label for="link_of_prescription">Link of Prescription:</label>
                <input type="url" id="link_of_prescription" name="link_of_prescription" required><br><br>
                <button type="submit">Submit</button>
            </form>
        </div>
    </div>
</body>
</html>
