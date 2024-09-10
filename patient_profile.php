

<?php
session_start();
if ($_SESSION['userType'] != 'patient') {
    header("Location: login.html");
    exit();
}

// Database connections
$conn_patient = new mysqli("localhost", "root", "", "speech_therapy_clinic_patient");
$conn_approved = new mysqli("localhost", "root", "", "speech_therapy_clinic_approved");
$conn_appointment = new mysqli("localhost", "root", "", "speech_therapy_clinic_pending_appointment");

// Check connections
if ($conn_patient->connect_error) {
    die("Connection failed (patient database): " . $conn_patient->connect_error);
}
if ($conn_approved->connect_error) {
    die("Connection failed (approved database): " . $conn_approved->connect_error);
}
if ($conn_appointment->connect_error) {
    die("Connection failed (pending appointment database): " . $conn_appointment->connect_error);
}

// Fetch patient data
$email = $_SESSION['email'];
$sql = "SELECT * FROM patients WHERE email='$email'";
$result = $conn_patient->query($sql);
$user = $result->fetch_assoc();

$doctorData = null;
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['doctor_name'])) {
    $doctor_name = $conn_approved->real_escape_string($_POST['doctor_name']);
    
    // Query the approved database for doctors including rating
    $sql_approved = "SELECT * FROM approved_therapists WHERE fullname LIKE '%$doctor_name%'";
    $result_approved = $conn_approved->query($sql_approved);
    
    if ($result_approved->num_rows > 0) {
        $doctorData = $result_approved->fetch_all(MYSQLI_ASSOC);
    } else {
        $doctorData = "No doctor found with that name.";
    }
}

// Handle appointment submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['book_appointment'])) {
    $patient_name = $conn_appointment->real_escape_string($_POST['patient_name']);
    $patient_phone = $conn_appointment->real_escape_string($_POST['patient_phone']);
    $preferred_date = $conn_appointment->real_escape_string($_POST['preferred_date']);
    $preferred_time = $conn_appointment->real_escape_string($_POST['preferred_time']);
    $disease = $conn_appointment->real_escape_string($_POST['disease']);
    $about = $conn_appointment->real_escape_string($_POST['about']);
    $therapist_name = $conn_appointment->real_escape_string($_POST['therapist_name']);
    $username = $conn_appointment->real_escape_string($user['fullname']);

    $sql_appointment = "INSERT INTO pending_appointment (username, patient_name, patient_phone, preferred_date, preferred_time, disease, about, therapist_name) 
                        VALUES ('$username', '$patient_name', '$patient_phone', '$preferred_date', '$preferred_time', '$disease', '$about', '$therapist_name')";
    
    if ($conn_appointment->query($sql_appointment) === TRUE) {
        echo "<script>alert('Appointment booked successfully!');</script>";
    } else {
        echo "Error: " . $sql_appointment . "<br>" . $conn_appointment->error;
    }
}

$conn_patient->close();
$conn_approved->close();
$conn_appointment->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Dashboard</title>
    <style>
        .profile-button {
            position: fixed;
            top: 10px;
            right: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
        }
        .modal, .appointment-modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }
        .modal-content, .appointment-modal-content {
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
        input[type="text"], input[type="date"], input[type="time"] {
            padding: 10px;
            width: calc(100% - 22px);
            margin-bottom: 10px;
        }
        input[type="submit"], .book-button {
            padding: 10px 20px;
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
    <script>
        function showModal() {
            document.getElementById("profileModal").style.display = "block";
        }
        function closeModal() {
            document.getElementById("profileModal").style.display = "none";
        }
        function showAppointmentModal(therapistName) {
            document.getElementById("therapistName").value = therapistName;
            document.getElementById("appointmentModal").style.display = "block";
        }
        function closeAppointmentModal() {
            document.getElementById("appointmentModal").style.display = "none";
        }
        window.onclick = function(event) {
            if (event.target == document.getElementById("profileModal")) {
                document.getElementById("profileModal").style.display = "none";
            }
            if (event.target == document.getElementById("appointmentModal")) {
                document.getElementById("appointmentModal").style.display = "none";
            }
        }
    </script>
</head>
<body>
    <h2>Welcome, Patient</h2>
    <p>Your email: <?php echo htmlspecialchars($user['email']); ?></p>
    <button class="profile-button" onclick="showModal()">Profile</button>

    <div id="profileModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Profile Details</h2>
            <p><strong>Full Name:</strong> <?php echo htmlspecialchars($user['fullname']); ?></p>
            <p><strong>Gender:</strong> <?php echo htmlspecialchars($user['gender']); ?></p>
            <p><strong>Contact No.:</strong> <?php echo htmlspecialchars($user['contact']); ?></p>
            <p><strong>Email ID:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
        </div>
    </div>

    <h2>Search for Doctors</h2>
    <form method="POST">
        <label for="doctor_name">Enter Doctor's Name:</label>
        <input type="text" id="doctor_name" name="doctor_name" required>
        <input type="submit" value="Search">
    </form>

    <?php if ($doctorData): ?>
        <?php if (is_array($doctorData)): ?>
            <table>
                <tr>
                    <th>Full Name</th>
                    <th>Date of Birth</th>
                    <th>Gender</th>
                    <th>Contact No.</th>
                    <th>License Number</th>
                    <th>Education</th>
                    <th>Experience</th>
                    <th>Specializations</th>
                    <th>Photo</th>
                    <th>Certificate</th>
                    <th>Rating</th> <!-- New Column for Rating -->
                </tr>
                <?php foreach ($doctorData as $doctor): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($doctor['fullname']); ?></td>
                        <td><?php echo htmlspecialchars($doctor['dob']); ?></td>
                        <td><?php echo htmlspecialchars($doctor['gender']); ?></td>
                        <td><?php echo htmlspecialchars($doctor['contact']); ?></td>
                        <td><?php echo htmlspecialchars($doctor['license']); ?></td>
                        <td><?php echo htmlspecialchars($doctor['education']); ?></td>
                        <td><?php echo htmlspecialchars($doctor['experience']); ?></td>
                        <td><?php echo htmlspecialchars($doctor['specialization']); ?></td>
                        <td><?php echo !empty($doctor['photo']) ? htmlspecialchars($doctor['photo']) : 'null'; ?></td>
                        <td><?php echo !empty($doctor['certificate']) ? htmlspecialchars($doctor['certificate']) : 'null'; ?></td>
                        <td><?php echo htmlspecialchars($doctor['rating']); ?></td> <!-- Display Rating -->
                    </tr>
                    <?php endforeach; ?>
            </table>
            <button class="book-button" onclick="showAppointmentModal('<?php echo htmlspecialchars($doctorData[0]['fullname']); ?>')">Book Appointment</button>
        <?php else: ?>
            <p><?php echo htmlspecialchars($doctorData); ?></p>
        <?php endif; ?>
    <?php endif; ?>

    <!-- Appointment Modal -->
    <div id="appointmentModal" class="appointment-modal">
        <div class="appointment-modal-content">
            <span class="close" onclick="closeAppointmentModal()">&times;</span>
            <h2>Book an Appointment</h2>
            <form method="POST">
                <input type="hidden" id="therapistName" name="therapist_name" value="">
                <label for="patient_name">Patient Name:</label>
                <input type="text" id="patient_name" name="patient_name" value="<?php echo htmlspecialchars($user['fullname']); ?>" required>

                <label for="patient_phone">Patient Phone No:</label>
                <input type="text" id="patient_phone" name="patient_phone" value="<?php echo htmlspecialchars($user['contact']); ?>" required>

                <label for="preferred_date">Preferred Date:</label>
                <input type="date" id="preferred_date" name="preferred_date" required>

                <label for="preferred_time">Preferred Time:</label>
                <input type="time" id="preferred_time" name="preferred_time" required>

                <label for="disease">Disease:</label>
                <input type="text" id="disease" name="disease" required>

                <label for="about">About:</label>
                <input type="text" id="about" name="about" required>

                <input type="submit" name="book_appointment" value="Submit">
            </form>
        </div>
    </div>
</body>
</html>
