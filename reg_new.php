<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'speech_therapy_clinic');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Sanitize input
function sanitize($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Check if email already exists
function emailExists($conn, $email, $userType) {
    $sql = "SELECT * FROM $userType WHERE email='$email'";
    $result = $conn->query($sql);
    return $result->num_rows > 0;
}

// Insert user data
function insertUser($conn, $userType, $fullname, $dob, $gender, $contact, $email, $password, $extraFields = []) {
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    $sql = "INSERT INTO $userType (fullname, dob, gender, contact, email, password";

    // Add extra fields dynamically
    foreach ($extraFields as $field => $value) {
        $sql .= ", $field";
    }

    $sql .= ") VALUES ('$fullname', '$dob', '$gender', '$contact', '$email', '$hashed_password'";

    foreach ($extraFields as $field => $value) {
        $sql .= ", '$value'";
    }

    $sql .= ")";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Registration successful! Please check your email for OTP.');</script>";
        // Redirect to OTP verification page or similar
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userType = sanitize($_POST['userType']);
    $fullname = sanitize($_POST['fullname']);
    $dob = sanitize($_POST['dob']);
    $gender = sanitize($_POST['gender']);
    $contact = sanitize($_POST['contact']);
    $email = sanitize($_POST['email']);
    $password = sanitize($_POST['password']);
    $confirm_password = sanitize($_POST['confirm_password']);

    if ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match!');</script>";
    } else {
        if (emailExists($conn, $email, $userType)) {
            echo "<script>alert('Email already exists!');</script>";
        } else {
            $extraFields = [];

            if ($userType === 'therapist' || $userType === 'supervisor') {
                $extraFields['license'] = sanitize($_POST['license']);
                $extraFields['qualification'] = sanitize($_POST['qualification']);
                $extraFields['experience'] = sanitize($_POST['experience']);
            }

            insertUser($conn, $userType, $fullname, $dob, $gender, $contact, $email, $password, $extraFields);

            // Send OTP via Flask server
            $otpSent = file_get_contents("http://localhost:5000/send_otp?email=$email");
            if ($otpSent == "Success") {
                echo "<script>alert('OTP sent to your email. Please verify.');</script>";
            } else {
                echo "<script>alert('Failed to send OTP. Please try again.');</script>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Speech Language Therapy Services</title>
    <script>
        function showForm() {
            var userType = document.getElementById("userType").value;
            document.getElementById("patientForm").style.display = "none";
            document.getElementById("therapistForm").style.display = "none";
            document.getElementById("supervisorForm").style.display = "none";
            document.getElementById("adminForm").style.display = "none";
            if (userType == "patient") {
                document.getElementById("patientForm").style.display = "block";
            } else if (userType == "therapist") {
                document.getElementById("therapistForm").style.display = "block";
            } else if (userType == "supervisor") {
                document.getElementById("supervisorForm").style.display = "block";
            } else if (userType == "admin") {
                document.getElementById("adminForm").style.display = "block";
            }
        }
    </script>
</head>
<body>
    <h2>Sign Up</h2>
    <label for="userType">Select User Type:</label>
    <select id="userType" name="userType" onchange="showForm()" required>
        <option value="">--Select User Type--</option>
        <option value="patient">Patient</option>
        <option value="therapist">Therapist</option>
        <option value="supervisor">Supervisor</option>
        <option value="admin">Admin</option>
    </select>
    <br><br>

    <!-- Patient Registration Form -->
    <form id="patientForm" action="reg.php" method="post" style="display:none;">
        <h3>Patient Registration</h3>
        <input type="hidden" name="userType" value="patient">
        <label for="fullname">Full Name:</label>
        <input type="text" id="fullname" name="fullname" required><br><br>
        <label for="dob">Date of Birth:</label>
        <input type="date" id="dob" name="dob" required><br><br>
        <label for="gender">Gender:</label>
        <input type="text" id="gender" name="gender" required><br><br>
        <label for="contact">Contact No.:</label>
        <input type="text" id="contact" name="contact" required><br><br>
        <label for="email">Email ID:</label>
        <input type="email" id="email" name="email" required><br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>
        <label for="confirm_password">Confirm Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" required><br><br>
        <button type="submit">Sign Up</button>
    </form>

    <!-- Therapist Registration Form -->
    <form id="therapistForm" action="reg.php" method="post" style="display:none;">
        <h3>Therapist Registration</h3>
        <input type="hidden" name="userType" value="therapist">
        <label for="fullname">Full Name:</label>
        <input type="text" id="fullname" name="fullname" required><br><br>
        <label for="dob">Date of Birth:</label>
        <input type="date" id="dob" name="dob" required><br><br>
        <label for="gender">Gender:</label>
        <input type="text" id="gender" name="gender" required><br><br>
        <label for="contact">Contact No.:</label>
        <input type="text" id="contact" name="contact" required><br><br>
        <label for="license">Professional License Number:</label>
        <input type="text" id="license" name="license" required><br><br>
        <label for="qualification">Qualification:</label>
        <input type="text" id="qualification" name="qualification" required><br><br>
        <label for="experience">Years of Experience:</label>
        <input type="number" id="experience" name="experience" required><br><br>
        <label for="email">Email ID:</label>
        <input type="email" id="email" name="email" required><br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>
        <label for="confirm_password">Confirm Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" required><br><br>
        <button type="submit">Sign Up</button>
    </form>

    <!-- Supervisor Registration Form -->
    <form id="supervisorForm" action="reg.php" method="post" style="display:none;">
        <h3>Supervisor Registration</h3>
        <input type="hidden" name="userType" value="supervisor">
        <label for="fullname">Full Name:</label>
        <input type="text" id="fullname" name="fullname" required><br><br>
        <label for="dob">Date of Birth:</label>
        <input type="date" id="dob" name="dob" required><br><br>
        <label for="gender">Gender:</label>
        <input type="text" id="gender" name="gender" required><br><br>
        <label for="contact">Contact No.:</label>
        <input type="text" id="contact" name="contact" required><br><br>
    <label for="license">Professional License Number:</label>
    <input type="text" id="license" name="license" required><br><br>
    <label for="qualification">Qualification:</label>
    <input type="text" id="qualification" name="qualification" required><br><br>
    <label for="experience">Years of Experience:</label>
    <input type="number" id="experience" name="experience" required><br><br>
    <label for="email">Email ID:</label>
    <input type="email" id="email" name="email" required><br><br>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>
    <label for="confirm_password">Confirm Password:</label>
    <input type="password" id="confirm_password" name="confirm_password" required><br><br>
    <button type="submit">Sign Up</button>
</form>

<!-- Admin Registration Form -->
<form id="adminForm" action="reg.php" method="post" style="display:none;">
    <h3>Admin Registration</h3>
    <input type="hidden" name="userType" value="admin">
    <label for="fullname">Full Name:</label>
    <input type="text" id="fullname" name="fullname" required><br><br>
    <label for="dob">Date of Birth:</label>
    <input type="date" id="dob" name="dob" required><br><br>
    <label for="gender">Gender:</label>
    <input type="text" id="gender" name="gender" required><br><br>
    <label for="contact">Contact No.:</label>
    <input type="text" id="contact" name="contact" required><br><br>
    <label for="email">Email ID:</label>
    <input type="email" id="email" name="email" required><br><br>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required><br><br>
    <label for="confirm_password">Confirm Password:</label>
    <input type="password" id="confirm_password" name="confirm_password" required><br><br>
    <button type="submit">Sign Up</button>
</form>
    </body>
    </html>
    