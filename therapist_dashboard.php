<?php
session_start();
if ($_SESSION['userType'] != 'therapist') {
    header("Location: login.html");
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "", "speech_therapy_clinic_therapist");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = $_SESSION['email'];
$sql = "SELECT * FROM therapists WHERE email='$email'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();

// Create chat database if it doesn't exist
$chatConn = new mysqli("localhost", "root", "", "speech_therapy_clinic_chat");
if ($chatConn->connect_error) {
    die("Connection failed: " . $chatConn->connect_error);
}

$createTableQuery = "CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    therapist_name VARCHAR(255) NOT NULL,
    patient_name VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($chatConn->query($createTableQuery) !== TRUE) {
    die("Error creating table: " . $chatConn->error);
}

$chatConn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Therapist Dashboard</title>
    <style>
        .profile-button, .schedule-button, .chat-button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            margin-top: 20px;
        }
        .profile-button {
            position: fixed;
            top: 10px;
            right: 10px;
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
        function showModal() {
            document.getElementById("profileModal").style.display = "block";
        }
        function showChatModal() {
            document.getElementById("chatModal").style.display = "block";
            loadPatients();
        }
        function closeModal(modalId) {
            document.getElementById(modalId).style.display = "none";
        }
        function loadPatients() {
            const patientSelect = document.getElementById('patient_name');
            fetch('get_patients.php')
                .then(response => response.json())
                .then(data => {
                    patientSelect.innerHTML = '';
                    data.forEach(patient => {
                        const option = document.createElement('option');
                        option.value = patient;
                        option.textContent = patient;
                        patientSelect.appendChild(option);
                    });
                });
        }
        function openSchedules() {
            window.open('check_schedule.php?therapist_name=<?php echo urlencode($user['fullname']); ?>', 'popupWindow', 'width=800,height=600');
        }
    </script>
</head>
<body>
    <h2>Welcome, Therapist</h2>
    <p>Your email: <?php echo htmlspecialchars($user['email']); ?></p>
    <button class="profile-button" onclick="showModal()">Profile</button>
    <button class="schedule-button" onclick="openSchedules()">Schedules</button>
    <button class="chat-button" onclick="showChatModal()">Chat</button>

    <!-- Profile Modal -->
    <div id="profileModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('profileModal')">&times;</span>
            <h2>Profile Details</h2>
            <p><strong>Full Name:</strong> <?php echo htmlspecialchars($user['fullname']); ?></p>
            <p><strong>Date of Birth:</strong> <?php echo htmlspecialchars($user['dob']); ?></p>
            <p><strong>Gender:</strong> <?php echo htmlspecialchars($user['gender']); ?></p>
            <p><strong>Contact No.:</strong> <?php echo htmlspecialchars($user['contact']); ?></p>
            <p><strong>Professional License Number:</strong> <?php echo htmlspecialchars($user['license']); ?></p>
            <p><strong>Educational Background:</strong> <?php echo htmlspecialchars($user['education']); ?></p>
            <p><strong>Years of Experience:</strong> <?php echo htmlspecialchars($user['experience']); ?></p>
            <p><strong>Specializations:</strong> <?php echo htmlspecialchars($user['specialization']); ?></p>
            <p><strong>Email ID:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
        </div>
    </div>

    <!-- Chat Modal -->
    <div id="chatModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('chatModal')">&times;</span>
            <h2>Send a Message</h2>
            <form action="send_message.php" method="POST">
                <label for="patient_name">Patient Name:</label>
                <select name="patient_name" id="patient_name" required>
                    <option value="">Select a Patient</option>
                </select>
                <br><br>
                <label for="message">Message:</label>
                <textarea name="message" id="message" rows="5" cols="50" required></textarea>
                <br><br>
                <button type="submit">Send Message</button>
            </form>
        </div>
    </div>
</body>
</html>
