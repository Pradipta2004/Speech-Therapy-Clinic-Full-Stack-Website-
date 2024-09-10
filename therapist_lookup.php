<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";

// Database connections
$conn_therapist = new mysqli($servername, $username, $password, "speech_therapy_clinic_therapist");
$conn_approved = new mysqli($servername, $username, $password, "speech_therapy_clinic_approved");

// Check connections
if ($conn_therapist->connect_error) {
    die("Connection failed (therapist database): " . $conn_therapist->connect_error);
}
if ($conn_approved->connect_error) {
    die("Connection failed (approved database): " . $conn_approved->connect_error);
}

$therapistData = null;

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['therapist_name'])) {
    $therapist_name = $conn_therapist->real_escape_string($_POST['therapist_name']);
    
    // Query the therapist database
    $sql_therapist = "SELECT * FROM therapists WHERE fullname = '$therapist_name'";
    $result_therapist = $conn_therapist->query($sql_therapist);
    
    if ($result_therapist->num_rows > 0) {
        $therapistData = $result_therapist->fetch_assoc();
    } else {
        // Query the approved database
        $sql_approved = "SELECT * FROM approved_therapists WHERE fullname = '$therapist_name'";
        $result_approved = $conn_approved->query($sql_approved);
        
        if ($result_approved->num_rows > 0) {
            $therapistData = $result_approved->fetch_assoc();
        } else {
            $therapistData = "No therapist found with that name.";
        }
    }
}

$conn_therapist->close();
$conn_approved->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Therapist Lookup</title>
    <style>
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            font-family: Arial, sans-serif;
        }
        input[type="text"] {
            padding: 10px;
            width: calc(100% - 22px);
            margin-bottom: 10px;
        }
        input[type="submit"] {
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
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
    <div class="container">
        <h1>Therapist Lookup</h1>
        <form method="POST">
            <label for="therapist_name">Enter Therapist Name:</label>
            <input type="text" id="therapist_name" name="therapist_name" required>
            <input type="submit" value="Search">
        </form>

        <?php if ($therapistData): ?>
            <?php if (is_array($therapistData)): ?>
                <table>
                    <tr>
                        <th>Full Name</th>
                        <td><?php echo htmlspecialchars($therapistData['fullname']); ?></td>
                    </tr>
                    <tr>
                        <th>Date of Birth</th>
                        <td><?php echo htmlspecialchars($therapistData['dob']); ?></td>
                    </tr>
                    <tr>
                        <th>Gender</th>
                        <td><?php echo htmlspecialchars($therapistData['gender']); ?></td>
                    </tr>
                    <tr>
                        <th>Contact No.</th>
                        <td><?php echo htmlspecialchars($therapistData['contact']); ?></td>
                    </tr>
                    <tr>
                        <th>License Number</th>
                        <td><?php echo htmlspecialchars($therapistData['license']); ?></td>
                    </tr>
                    <tr>
                        <th>Education</th>
                        <td><?php echo htmlspecialchars($therapistData['education']); ?></td>
                    </tr>
                    <tr>
                        <th>Experience</th>
                        <td><?php echo htmlspecialchars($therapistData['experience']); ?></td>
                    </tr>
                    <tr>
                        <th>Specializations</th>
                        <td><?php echo htmlspecialchars($therapistData['specialization']); ?></td>
                    </tr>
                    <tr>
                        <th>Photo</th>
                        <td><?php echo !empty($therapistData['photo']) ? htmlspecialchars($therapistData['photo']) : 'null'; ?></td>
                    </tr>
                    <tr>
                        <th>Certificate</th>
                        <td><?php echo !empty($therapistData['certificate']) ? htmlspecialchars($therapistData['certificate']) : 'null'; ?></td>
                    </tr>
                </table>
            <?php else: ?>
                <p><?php echo htmlspecialchars($therapistData); ?></p>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</body>
</html>
