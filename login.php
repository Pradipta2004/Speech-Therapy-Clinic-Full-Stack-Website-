<?php
// Database connection setup
function getDatabaseConnection($userType) {
    $databases = [
        'patient' => 'speech_therapy_clinic_patient',
        'therapist' => 'speech_therapy_clinic_therapist',
        'supervisor' => 'speech_therapy_clinic_supervisor',
        'admin' => 'speech_therapy_clinic_admin'
    ];

    $conn = new mysqli("localhost", "root", "", $databases[$userType]);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}

// Collecting form data
$userType = $_POST['userType'];
$email = $_POST['email'];
$password = $_POST['password'];

// Get database connection based on user type
$conn = getDatabaseConnection($userType);

// Query to check user credentials
$sql = "SELECT * FROM " . ($userType . "s") . " WHERE email='$email' AND password='$password'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    session_start();
    $_SESSION['userType'] = $userType;
    $_SESSION['email'] = $email;
    
    switch ($userType) {
        case 'patient':
            header("Location: patient_dashboard.php");
            break;
        case 'therapist':
            header("Location: therapist_dashboard.php");
            break;
        case 'supervisor':
            header("Location: supervisor_dashboard.php");
            break;
        case 'admin':
            header("Location: admin_dashboard.php");
            break;
    }
} else {
    echo "Invalid email or password. <a href='login.html'>Try again</a>";
}

$conn->close();
?>