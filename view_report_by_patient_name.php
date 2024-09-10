<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "speech_therapy_clinic_scheduled_appointment");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if (isset($_POST['patient_name'])) {
    $patient_name = $_POST['patient_name'];

    // Prepare the SQL statement
    $sql = "SELECT * FROM therapist_reports WHERE patient_name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $patient_name);
    $stmt->execute();
    $result = $stmt->get_result();

    // Display the results
    if ($result->num_rows > 0) {
        echo "<h2>Reports for Patient: " . htmlspecialchars($patient_name) . "</h2>";
        echo "<table border='1'>";
        echo "<tr>
                <th>ID</th>
                <th>Therapist Name</th>
                <th>Progress Report Period</th>
                <th>Date Report Was Written</th>
                <th>Diagnoses</th>
                <th>Previous Level of Functioning</th>
                <th>Current Level of Functioning</th>
                <th>Prognosis</th>
                <th>Plan of Care: Patient Goals</th>
                <th>Plan of Care: Therapeutic Intervention</th>
                <th>Goals</th>
                <th>Recommendations</th>
                <th>Link of Prescription</th>
              </tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['id']) . "</td>";
            echo "<td>" . htmlspecialchars($row['therapist_name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['progress_report_period']) . "</td>";
            echo "<td>" . htmlspecialchars($row['date_report_written']) . "</td>";
            echo "<td>" . htmlspecialchars($row['diagnoses']) . "</td>";
            echo "<td>" . htmlspecialchars($row['previous_level_of_functioning']) . "</td>";
            echo "<td>" . htmlspecialchars($row['current_level_of_functioning']) . "</td>";
            echo "<td>" . htmlspecialchars($row['prognosis']) . "</td>";
            echo "<td>" . htmlspecialchars($row['plan_of_care_patient_goals']) . "</td>";
            echo "<td>" . htmlspecialchars($row['plan_of_care_therapeutic_intervention']) . "</td>";
            echo "<td>" . htmlspecialchars($row['goals']) . "</td>";
            echo "<td>" . htmlspecialchars($row['recommendations']) . "</td>";
            echo "<td><a href='" . htmlspecialchars($row['link_of_prescription']) . "' target='_blank'>View Prescription</a></td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "No reports found for the specified patient.";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Reports by Patient Name</title>
</head>
<style>
    /* General Body Styling */
body {
    font-family: Arial, sans-serif;
    background-color: #f4f7f6;
    color: #333;
    margin: 0;
    padding: 20px;
}

/* Heading */
h1 {
    text-align: center;
    color: #4CAF50;
    font-size: 32px;
    margin-bottom: 30px;
}

/* Form Styling */
form {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-bottom: 30px;
}

label {
    margin-right: 10px;
    font-size: 18px;
}

input[type="text"] {
    padding: 10px;
    font-size: 16px;
    border-radius: 4px;
    border: 1px solid #ccc;
    margin-right: 10px;
}

button {
    padding: 10px 20px;
    background-color: #4CAF50;
    color: white;
    border: none;
    font-size: 16px;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

button:hover {
    background-color: #45a049;
}

/* Table Styling */
table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
    font-size: 18px;
    text-align: left;
    background-color: #fff;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

table th, table td {
    padding: 12px 15px;
    border-bottom: 1px solid #ddd;
}

table th {
    background-color: #4CAF50;
    color: white;
    text-transform: uppercase;
}

table tr:hover {
    background-color: #f5f5f5;
}

table tr:nth-child(even) {
    background-color: #f9f9f9;
}

/* Links */
a {
    color: #4CAF50;
    text-decoration: none;
    transition: color 0.3s ease;
}

a:hover {
    color: #3e8e41;
}

/* Responsive Design */
@media screen and (max-width: 768px) {
    table {
        font-size: 16px;
    }

    form {
        flex-direction: column;
        align-items: flex-start;
    }

    input[type="text"] {
        margin-bottom: 10px;
        width: 100%;
    }

    button {
        width: 100%;
    }
}

</style>
<body>
    <h1>Search Reports by Patient Name</h1>
    <form method="POST" action="">
        <label for="patient_name">Enter Patient Name:</label>
        <input type="text" id="patient_name" name="patient_name" required>
        <button type="submit">Search</button>
    </form>
</body>
</html>
