

<!-- appointment not working -->



<?php
session_start();
if ($_SESSION['userType'] != 'supervisor') {
    header("Location: login.html");
    exit();
}

// Database connection to supervisor database
$conn = new mysqli("localhost", "root", "", "speech_therapy_clinic_supervisor");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = $_SESSION['email'];
$sql = "SELECT * FROM supervisors WHERE email='$email'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supervisor Dashboard</title>
    <style>
        .profile-button, .approval-button, .license-button, .pending-appointment-button, .rating-button, .logout-button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            margin-top: 20px;
        }
        .approval-button {
            background-color: #2196F3;
        }
        .rating-button {
            background-color: #FF5722;
        }
        .logout-button {
            background-color: #f44336;
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
        function showProfileModal() {
            document.getElementById("profileModal").style.display = "block";
        }
        function showApprovalModal() {
            document.getElementById("approvalModal").style.display = "block";
        }
        function showPendingAppointmentModal() {
            document.getElementById("pendingAppointmentModal").style.display = "block";
        }
        function showRatingModal() {
            document.getElementById("ratingModal").style.display = "block";
        }
        function openLicenseLookup() {
            window.open('therapist_lookup.php', 'popupWindow', 'width=800,height=600');
        }
        function closeModal(modalId) {
            document.getElementById(modalId).style.display = "none";
        }
        window.onclick = function(event) {
            if (event.target == document.getElementById("profileModal")) {
                closeModal("profileModal");
            } else if (event.target == document.getElementById("approvalModal")) {
                closeModal("approvalModal");
            } else if (event.target == document.getElementById("pendingAppointmentModal")) {
                closeModal("pendingAppointmentModal");
            } else if (event.target == document.getElementById("ratingModal")) {
                closeModal("ratingModal");
            }
        }
    </script>
</head>
<body>
    <h2>Welcome, Supervisor</h2>
    <p>Your email: <?php echo htmlspecialchars($user['email']); ?></p>
    <button class="profile-button" onclick="showProfileModal()">Profile</button>
    <button class="approval-button" onclick="showApprovalModal()">Pending Approval</button>
    <button class="license-button" onclick="openLicenseLookup()">Check License</button>
    <button class="pending-appointment-button" onclick="showPendingAppointmentModal()">Pending Appointments</button>
    <button class="rating-button" onclick="showRatingModal()">Rating</button>
    <a href="logout.php"><button class="logout-button">Logout</button></a>

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

    <!-- Pending Approval Modal -->
    <div id="approvalModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('approvalModal')">&times;</span>
            <h2>Pending Approval</h2>
            <?php
            // Database connection to therapist database
            $therapist_conn = new mysqli("localhost", "root", "", "speech_therapy_clinic_therapist");

            if ($therapist_conn->connect_error) {
                die("Connection failed: " . $therapist_conn->connect_error);
            }

            $sql = "SELECT id, fullname, email FROM therapists";
            $result = $therapist_conn->query($sql);

            if ($result->num_rows > 0) {
                echo "<table border='1'>";
                echo "<tr><th>Full Name</th><th>Email</th><th>Action</th></tr>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['fullname']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                    echo "<td>";
                    echo "<form action='approve_therapist.php' method='POST' style='display:inline;'>";
                    echo "<input type='hidden' name='id' value='" . $row['id'] . "'>";
                    echo "<button type='submit'>Approve</button>";
                    echo "</form>";
                    echo " | ";
                    echo "<form action='reject_therapist.php' method='POST' style='display:inline;'>";
                    echo "<input type='hidden' name='id' value='" . $row['id'] . "'>";
                    echo "<button type='submit'>Reject</button>";
                    echo "</form>";
                    echo "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "No therapists pending approval.";
            }

            $therapist_conn->close();
            ?>
        </div>
    </div>

    <!-- Pending Appointments Modal -->
    <div id="pendingAppointmentModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('pendingAppointmentModal')">&times;</span>
            <h2>Pending Appointments</h2>
            <?php
            // Database connection to pending_appointment database
            $appointment_conn = new mysqli("localhost", "root", "", "speech_therapy_clinic_patient");

            if ($appointment_conn->connect_error) {
                die("Connection failed: " . $appointment_conn->connect_error);
            }

            $sql = "SELECT * FROM pending_appointment";
            $result = $appointment_conn->query($sql);

            if ($result->num_rows > 0) {
                echo "<table border='1'>";
                echo "<tr><th>Username</th><th>Therapist Name</th><th>Patient Name</th><th>Phone Number</th><th>Preferred Date</th><th>Preferred Time</th><th>Disease</th><th>About</th><th>Action</th></tr>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['therapist_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['patient_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['patient_phone']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['preferred_date']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['preferred_time']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['disease']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['about']) . "</td>";
                    echo "<td><button>Schedule</button></td>"; // Placeholder for future functionality
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "No pending appointments.";
            }

            $appointment_conn->close();
            ?>
        </div>
    </div>

    <!-- Rating Modal -->
    <div id="ratingModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('ratingModal')">&times;</span>
            <h2>Rate Therapist</h2>
            <form action="submit_rating.php" method="POST">
                <label for="therapist_id">Select Therapist:</label>
                <select name="therapist_id" id="therapist_id" required>
                    <?php
                    // Populate the dropdown with therapists
                    $therapist_conn = new mysqli("localhost", "root", "", "speech_therapy_clinic_approved");

                    if ($therapist_conn->connect_error) {
                        die("Connection failed: " . $therapist_conn->connect_error);
                    }

                    $sql = "SELECT id, fullname FROM approved_therapists";
                    $result = $therapist_conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['id'] . "'>" . htmlspecialchars($row['fullname']) . "</option>";
                        }
                    }

                    $therapist_conn->close();
                    ?>
                </select>
                <br><br>
                <label for="rating">Rating (1-5):</label>
                <input type="number" name="rating" id="rating" min="1" max="5" required>
                <br><br>
                <button type="submit">Submit Rating</button>
            </form>
        </div>
    </div>
</body>
</html>

