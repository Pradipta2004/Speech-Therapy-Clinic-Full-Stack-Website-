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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bungee+Tint&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Nerko+One&display=swap" rel="stylesheet">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Nerko+One&display=swap" rel="stylesheet">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Merriweather:ital,wght@0,300;0,400;0,700;0,900;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">
    <style>
        .profile-button, .approval-button, .license-button, .logout-button, .pending-appointment-button, .manage-button, .rating-button, .report-button {
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
        .logout-button {
            background-color: #f44336;
        }
        .pending-appointment-button {
            background-color: #FF9800;
        }
        .manage-button {
            background-color: #9C27B0;
        }
        .rating-button {
            background-color: #FF5722;
        }
        .report-button {
            background-color: #009688;
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
        h2{
            /* text-align: center; */
            font-family: "Nerko One", cursive;
  font-weight: 400;
  font-size: 50px;
  color: #033e9c;
  font-style: normal;
        }
        .btn {
            background: hsl(340deg 100% 32%);
            border: none;
            border-radius: 12px;
            padding: 0;
            cursor: pointer;
            outline-offset: 4px;
            width: 300px;
            height: 50px;
            margin: 10px;
        }
        span{
            display: block;
            padding: 12px 42px;
            border-radius: 12px;
            font-size: 1.25rem;
            background: hsl(345deg 100% 47%);
            color: white;
            transform: translateY(-6px);
            font-style: italic;
            font-size: 20px;
            font-family: "Quicksand";
        }
        .btn:active span{
            transform: translate(-2px);
        }
        .grid{
            display: grid;
            grid-template-columns: 400px 400px 400px;
            grid-template-rows: auto;
            gap:50px;
            max-width: fit-content;
            margin: auto;
        }
        a{
            text-decoration: none;
            color: white;
        }
        .profile{
            float: right;

            position: relative;
    top:50%;
    transform: translateY(25%);
        }
        .logout{
            float: right;
            margin-right: 30px;
    position: relative;
    top:50%;
    transform: translateY(25%);
        }
        .circular-btn {background-color: #1d4ed8; /* Blue background color */
    color: white; /* White text color */
    font-weight: bold;
    padding: 16px; /* Equal padding for circular shape */
    border-radius: 50%; /* Makes the button circular */
    border: none; /* Remove default border */
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1); /* Add shadow for depth */
    cursor: pointer; /* Pointer cursor on hover */
    transition: background-color 0.3s ease; /* Smooth background color transition */
}
/* Hover effect */
.circular-btn:hover {
    background-color: #1e40af; /* Darker blue on hover */
}

nav{
    background: #c9c3c3;
}

/* Navbar container styling */
.navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: linear-gradient(90deg, #1e3a8a, #3b82f6);
    padding: 10px 20px;
    border-radius: 10px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    color: white;
}

/* Welcome text styling */
.navbar h2 {
    margin: 0;
    font-size: 1.5rem;
    font-weight: bold;
}

/* Navigation bar styling */
nav {
    display: flex;
    gap: 15px;
}

/* Button styling */
.btn {
    background-color: #ffffff;
    color: #1e3a8a;
    border: 2px solid transparent;
    padding: 10px 20px;
    border-radius: 25px;
    font-weight: bold;
    font-size: 1rem;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
}

.btn:hover {
    background-color: #1e3a8a;
    color: #ffffff;
    border: 2px solid #ffffff;
}

/* Circular button styling */
.circular-btn {
    background-color: #1e3a8a;
    color: white;
    font-weight: bold;
    padding: 10px 15px;
    border-radius: 50%;
    border: none;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    cursor: pointer;
    transition: transform 0.2s ease, background-color 0.3s ease;
}

.circular-btn:hover {
    background-color: #3b82f6;
    transform: scale(1.1);
}

/* Link styling within buttons */
.circular-btn a {
    color: white;
    text-decoration: none;
}

/* Responsive layout */
@media (max-width: 768px) {
    .navbar {
        flex-direction: column;
        align-items: flex-start;
    }

    nav {
        flex-direction: column;
        width: 100%;
    }

    .btn, .circular-btn {
        width: 100%;
        text-align: center;
        margin-top: 10px;
    }
}
.aqua:hover{
    background: #40E0D0;
    color: #1e3a8a;
}
.schedule_app{
    height: 600px;
    width: 700px;
    border: 2px solid;
    margin-left:50px;
    font-family: Merriweather, serif;
    font-size:40px;
    box-shadow: 10px 10px;
}
.man_th{
    float: right;
    height: 600px;
    width: 700px;
    border: 2px solid;
    margin-right:50px;
    font-family: Merriweather, serif;
    font-size:40px;
    box-shadow: 10px 10px;
}
.pat_rep{
    height: 450px;
    width: 530px;
    border: 2px solid;
    margin-left:150px;
    font-family: Merriweather, serif;
    font-size:40px;
    padding-top: 25px;
    box-shadow: 10px 10px;
}
.pat_rep >p{
    padding: 0 30px;
    line-height: 2;
    font-size: 15px;
    font-style: italic;
}
.th_rep{
    float: right;
    height: 450px;
    width: 530px;
    border: 2px solid;
    margin-right:150px;
    font-family: Merriweather, serif;
    font-size:40px;
    padding-top: 25px;
    box-shadow: 10px 10px;
}
.th_rep >p{
    padding: 0 30px;
    line-height: 2;
    font-size: 15px;
    font-style: italic;
}
img{
    box-shadow: 0 0 20px blue;
    border-radius: 80px;
}
.mng_pat{
    /* background: silver; */
}
.chk_report{
    /* background: #42c79d; */
}
.extra{
    /* background: #fae5b6; */
}
.license{
    height: 100px;
    width: 600px;
    border: 2px solid;
    margin-left: 100px;
    font-family: Merriweather, serif;
    font-size: 40px;
    box-shadow: 10px 10px;
}
.rating{
    float: right;
    height: 100px;
    width: 600px;
    border: 2px solid;
    margin-right: 100px;
    font-family: Merriweather, serif;
    font-size: 40px;
    box-shadow: 10px 10px;
}
/* General Modal Styles */
.modal {
    display: none; 
    position: fixed; 
    z-index: 1; 
    left: 0;
    top: 0;
    width: 100%; 
    height: 100%; 
    overflow: auto; 
    background-color: rgba(0,0,0,0.4); 
}

.modal-content {
    background-color: #fefefe;
    margin: 5% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
    min-width: 1000px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    animation: fadeIn 0.5s ease-in-out;
}

/* Close Button */
.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
}

/* Table Styling */
table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
    font-size: 18px;
    text-align: left;
    border: 1px solid #ddd;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    background-color: #fff;
}

table th, table td {
    padding: 12px 15px;
    border-bottom: 1px solid #ddd;
    transition: background-color 0.2s ease;
}

/* Table Header */
table th {
    background-color: #f2f2f2;
    color: #333;
    text-transform: uppercase;
    letter-spacing: 0.03em;
}

/* Table Row Hover Effect */
table tr:hover {
    background-color: #f5f5f5;
}

/* Table Buttons */
button {
    background-color: #4CAF50;
    color: white;
    border: none;
    padding: 10px 15px;
    text-align: center;
    font-size: 16px;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

button:hover {
    background-color: #45a049;
}

/* Media Queries for Responsiveness */
@media screen and (max-width: 768px) {
    .modal-content {
        width: 95%;
    }

    table {
        font-size: 16px;
    }
}

/* Animation */
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}
.close{
    padding: 5px 15px;
}
        /* Modal styling */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* Dim background */
            overflow: auto;
        }

        .profile-button-round {
    display: flex;
    align-items: center;
            position: fixed;
            top: 30px;
            right: 50px;
            /* background-color: #2b6cb0; */
            color: white;
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            text-align: center;
            line-height: 40px;
            /* font-size: 18px; */
            cursor: pointer;
            z-index: 2;
            transition: background-color 0.3s ease;
        }
        .profile-button-round:hover {
            /* background-color: #1a496b; */
        }

        /* Close button */
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            transition: color 0.2s ease;
        }

        .close:hover,
        .close:focus {
            color: #333;
        }

        /* Form Styling */
        #ratingForm {
            font-family: 'Georgia', 'Times New Roman', Times, serif;
            font-size: 16px;
            color: #333;
        }

        label {
            font-size: 18px;
            font-weight: bold;
        }

        input[type="text"], input[type="number"] {
            width: 30%;
            padding: 10px;
            margin: 8px 0;
            border-radius: 8px;
            border: 1px solid #ccc;
            box-shadow: inset 0 3px 6px rgba(0, 0, 0, 0.1); /* Inset 3D effect */
            font-size: 16px;
            transition: all 0.3s ease;
        }

        input[type="text"]:hover, input[type="number"]:hover {
            box-shadow: 0 0 10px rgba(0, 128, 0, 0.3); /* Glowing border effect on hover */
        }

        input[type="text"]:focus, input[type="number"]:focus {
            outline: none;
            border-color: #4CAF50;
            box-shadow: 0 0 12px rgba(0, 128, 0, 0.6); /* Glowing border on focus */
        }

        button[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 12px 20px;
            margin-top: 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 18px;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2); /* 3D shadow for button */
        }

        button[type="submit"]:hover {
            background-color: #45a049;
            transform: translateY(-3px); /* Slight lift on hover */
            box-shadow: 0 8px 20px rgba(0, 128, 0, 0.5); /* Deeper shadow on hover */
        }

        .three-dot-menu {
            position: fixed;
            top: 30px;
            right: 15px;
            cursor: pointer;
            font-size: 22px;
            background: none;
            border: none;
            color: #2b6cb0;
            z-index: 2;
        }
        .three-dot-menu:hover{
            background: none;
        }
        .dropdown-content {
            display: none;
            position: absolute;
            top: 50px;
            right: 15px;
            background-color: #ffffff;
            min-width: 200px;
            box-shadow: 0px 8px 16px rgba(0,0,0,0.1);
            border-radius: 8px;
            z-index: 1;
        }
        .dropdown-content a {
            color: #333;
            padding: 14px 20px;
            text-decoration: none;
            display: block;
            border-bottom: 1px solid #ddd;
        }
        .dropdown-content a:last-child {
            border-bottom: none;
        }
        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }
        /* Styles for content below the background image */
.below-background {
    padding: 20px;
    margin-top: 20px;
}

.image-about-container {
    display: flex;
    margin-bottom: 20px;
    gap: 20px; /* Adds space between the image and about sections */
}

.image-section, .about-section {
    flex: 1;
    border: 2px solid #ccc;
    border-radius: 15px;
    padding: 20px;
    box-sizing: border-box;
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    overflow: hidden;
    height: 400px; /* Increased height */
    transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
}

.image-section {
    margin-right: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.image-section img {
    max-height: 100%;
    width: auto;
    border-radius: 10px;
    transition: transform 0.3s ease-in-out;
}

.image-section:hover img {
    transform: scale(1.05); /* Slight zoom effect on hover */
}

.about-section {
    margin-left: 20px;
}

.about-section h2 {
    margin-top: 0;
    color: #343a40;
    font-size: 24px;
}

.about-section p {
    font-size: 16px;
    line-height: 1.6;
    color: #495057;
}

.image-section:hover, .about-section:hover {
    transform: translateY(-5px) scale(1.02);
    box-shadow: 0 16px 32px rgba(0, 0, 0, 0.3);
}

/* Styles for content below the background image */


/* Flex container for centering */
.catalog-container {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: center; /* Centers the catalogs horizontally */
    margin: 0 auto; /* Centers the container itself */
}

.catalog {
    width: 200px; /* Fixed width for square shape */
    height: 200px; /* Fixed height for square shape */
    box-sizing: border-box;
    border: 2px solid #ddd;
    border-radius: 10px;
    overflow: hidden;
    cursor: pointer;
    transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
}

.catalog img {
    width: 100%;
    height: 60%; /* Adjusted to fit within the square */
    object-fit: cover; /* Ensures the image covers the area */
    border-bottom: 2px solid #ddd;
}

.catalog-content {
    padding: 10px;
    text-align: center;
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    border-top: 2px solid #ddd;
    height: 40%; /* Adjusted to fit within the square */
    display: flex;
    align-items: center;
    justify-content: center;
}

.catalog:hover {
    transform: translateY(-5px) scale(1.02);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.3);
}

        

        /* Footer Styling */
.footer {
    background-color: #1c1c1c;
    color: #f1f1f1;
    padding: 50px 20px;
    text-align: center;
    box-shadow: 0px -4px 10px rgba(0, 0, 0, 0.5);
}

.footer-container {
    display: flex;
    justify-content: space-around;
    flex-wrap: wrap;
    margin-bottom: 30px;
}

.footer-section {
    flex: 1;
    min-width: 200px;
    padding: 20px;
    border-radius: 10px;
    background: linear-gradient(145deg, #242424, #1a1a1a);
    box-shadow: 10px 10px 20px #141414, -10px -10px 20px #262626;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.footer-section h3 {
    font-size: 1.5em;
    margin-bottom: 15px;
    color: #ff9800;
}

.footer-section p, .footer-section ul, .footer-section a {
    font-size: 1em;
    color: #cfcfcf;
}

.footer-section ul {
    list-style: none;
    padding: 0;
}

.footer-section ul li {
    margin: 10px 0;
}

.footer-section ul li a {
    text-decoration: none;
    color: #ff9800;
    transition: color 0.3s;
}

.footer-section ul li a:hover {
    color: #ffffff;
}

.footer-section .social-icons {
    display: flex;
    justify-content: center;
}

.footer-section .social-icons a {
    margin: 0 10px;
    transition: transform 0.3s ease;
}

.footer-section .social-icons a img {
    width: 30px;
    height: 30px;
    filter: drop-shadow(3px 3px 5px #000);
}

.footer-section:hover {
    transform: translateY(-5px);
    box-shadow: 20px 20px 40px #0e0e0e, -20px -20px 40px #2a2a2a;
}

.footer-bottom {
    padding: 20px;
    background-color: #0e0e0e;
    border-top: 1px solid #333;
}

.footer-bottom p {
    font-size: 0.9em;
    margin: 0;
    color: #a1a1a1;
}
p{font-family: Merriweather, serif;}
    </style>
    <script>
        function showProfileModal() {
            document.getElementById("profileModal").style.display = "block";
        }
        function showApprovalModal() {
            document.getElementById("approvalModal").style.display = "block";
        }
        function showPendingAppointmentsModal() {
            document.getElementById("pendingAppointmentsModal").style.display = "block";
        }
        function openLicenseLookup() {
            window.open('therapist_lookup.php', 'popupWindow', 'width=800,height=600');
        }
        function openRatingModal() {
            document.getElementById("ratingModal").style.display = "block";
        }
        function closeModal(modalId) {
            document.getElementById(modalId).style.display = "none";
        }
        window.onclick = function(event) {
            if (event.target == document.getElementById("profileModal")) {
                closeModal("profileModal");
            } else if (event.target == document.getElementById("approvalModal")) {
                closeModal("approvalModal");
            } else if (event.target == document.getElementById("pendingAppointmentsModal")) {
                closeModal("pendingAppointmentsModal");
            } else if (event.target == document.getElementById("scheduleModal")) {
                closeModal("scheduleModal");
            } else if (event.target == document.getElementById("ratingModal")) {
                closeModal("ratingModal");
            }
        }
        function openScheduleModal(appointmentId) {
            document.getElementById('pendingAppointmentId').value = appointmentId;
            document.getElementById('scheduleModal').style.display = 'block';
        }
        function toggleDropdown() {
            document.getElementById("dropdownMenu").classList.toggle("show");
        }
        function openReport() {
            var therapistName = encodeURIComponent("<?php echo $user['fullname']; ?>");
            window.open('generate_report.php?therapist_name=' + therapistName, 'popupWindow', 'width=800,height=600');
            toggleDropdown();
        }
    </script>
</head>
<body>
    <!-- <h2>Welcome, Supervisor !!</h2> -->
    <nav class="navbar">
    <button class=" circular-btn profile-button-round " onclick="showProfileModal()" style="margin-right:80px;">P</button>
    <button class="btn aqua" style="margin-left: 20vw; font-size:20px; font-family: Merriweather, serif;" onclick="showPendingAppointmentsModal()">Pending Appointments</button>
    <button class="btn aqua" style="margin-left: 5vw; font-size:20px; font-family: Merriweather, serif;" onclick="showApprovalModal()">Pending Approval</button>
    <button class="circular-btn"><a href="logout.php">Logout</a></button> 
    </nav>

    <!-- New Buttons -->
    <div class="mng_pat"> <br><br><br><br><br>
    <a href="manage_scheduled_appointments.php" target="_blank"><button class="btn schedule_app">Schedule Patient <br> Appointments <br><br><img src="patient-appointment-scheduling-4.jpg" alt="" height=400px; width=600px;></button></a>
    <a href="manage.php" target="_blank"><button class="btn man_th" > Manage Appointments <br> for Therapists <br><br><img src="doctor-scheduling-software.png" alt="" width=600px; height=400px; ></button></a><br>
    <br><br><br><br><br></div>

    <div class="chk_report"> <br><br><br>
    <a href="view_report_by_patient_name.php"><button class="btn pat_rep"> Check Medical Report by Patient's Name <br><br><p>The patient medical report on this website provides a comprehensive overview of a patient's therapy progress, including detailed session notes, evaluations, and outcomes. It features easy access to historical data, ensuring both therapists and supervisors can track improvements over time. The report is designed to be user-friendly, with clear sections that summarize key insights, aiding in informed decision-making for continued care.</p></button></a>
    <a href="view_report_by_therapist_name.php"><button class="btn th_rep"> Check Medical Report by Therapist's Name <br><br><p>The medical report provided by the speech therapist on this website is a comprehensive document detailing the patient's assessment, therapy progress, and outcomes. It includes specific observations, therapy goals, session summaries, and recommendations for further treatment. This report ensures clear communication between therapists and patients, facilitating effective and personalized speech-language therapy.</p></button></a>
    <br><br><br><br></div>

    <div class="extra"> <br><br><br><br><br>
    <button class="btn license" onclick="openLicenseLookup()">Check Therapist's License</button>
    <button class="btn rating" onclick="openRatingModal()">Clinical Session Rating</button>
    <br><br><br><br><br><br><br></div>
    <!-- Profile Modal -->
    <div id="profileModal" class="modal">
        <div class="modal-content" style="width: 50px;">
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
    <div id="pendingAppointmentsModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('pendingAppointmentsModal')">&times;</span>
            <h2>Pending Appointments</h2>
            <?php
            // Database connection to pending_appointment database
            $appointment_conn = new mysqli("localhost", "root", "", "speech_therapy_clinic_pending_appointment");

            if ($appointment_conn->connect_error) {
                die("Connection failed: " . $appointment_conn->connect_error);
            }

            $sql = "SELECT * FROM pending_appointment";
            $result = $appointment_conn->query($sql);

            if ($result->num_rows > 0) {
                echo "<table border='1'>";
                echo "<tr><th>Username</th><th>Patient Name</th><th>Patient Phone</th><th>Preferred Date</th><th>Preferred Time</th><th>Disease</th><th>About</th><th>Therapist Name</th><th>Action</th></tr>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['patient_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['patient_phone']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['preferred_date']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['preferred_time']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['disease']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['about']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['therapist_name']) . "</td>";
                    echo "<td><button type='button' onclick='openScheduleModal(" . $row['id'] . ")'>Schedule</button></td>";
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

    <!-- Schedule Modal -->
    <div id="scheduleModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('scheduleModal')">&times;</span>
            <h2>Schedule Appointment</h2>
            <form id="scheduleForm" action="schedule_appointment.php" method="POST">
                <input type="hidden" name="pending_appointment_id" id="pendingAppointmentId">
                <label for="scheduled_date">Scheduled Date:</label>
                <input type="date" name="scheduled_date" id="scheduled_date" required>
                <br>
                <label for="scheduled_time">Scheduled Time:</label>
                <input type="time" name="scheduled_time" id="scheduled_time" required>
                <br><br>
                <button type="submit">Confirm Schedule</button>
            </form>
        </div>
    </div>

    <!-- Rating Modal -->
    <div id="ratingModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('ratingModal')">&times;</span>
            <h2>Rate Therapist</h2>
            <form id="ratingForm" action="rate_therapist.php" method="POST">
                <div><label for="therapist_name" style="font-family: Georgia, 'Times New Roman', Times, serif; margin-right:50px;" ><b>Therapist Name:</b></label>
                <input type="text" name="therapist_name" id="therapist_name" style="border: 2px solid;" required>
                </div><br><br>
                <div><label for="rating" style="font-family: Georgia, 'Times New Roman', Times, serif; margin-right:50px;" ><b>Rating (1-5):</b></label>
                <input type="number" name="rating" id="rating" min="1" max="5" style="border: 2px solid;" required>
                </div><br><br>
                <button type="submit" style="border: 2px solid black;" >Submit Rating</button>
            </form>
        </div>
    </div>
            <!-- Catalogs Section -->
            <div class="catalog-container">
            <div class="catalog">
                <img src="ai_case.jpg" alt="Catalog 1 Logo">
                <div class="catalog-content">AI-Assisted Therapy Review:</div>
            </div>
            <div class="catalog">
                <img src="automatic_case.jpeg" alt="Catalog 2 Logo">
                <div class="catalog-content">Automatic Case Allocation System</div>
            </div>
            <div class="catalog">
                <img src="catalog-logo3.jpeg" alt="Catalog 3 Logo">
                <div class="catalog-content">Session Monitoring</div>
            </div>
            <div class="catalog">
                <img src="catalog-logo4.jpg" alt="Catalog 4 Logo">
                <div class="catalog-content">Feedback</div>
            </div>
            <div class="catalog">
                <img src="catalog-logo5.png" alt="Catalog 5 Logo">
                <div class="catalog-content">Therapist Performance Dashboard</div>
            </div>
            <div class="catalog">
                <img src="catalog-logo6.jpeg" alt="Catalog 6 Logo">
                <div class="catalog-content">Resource & Blogs</div>
            </div>
        </div>
    </div>

    <!-- foter  -->
    
    
    
    <!-- Footer Section -->
<footer class="footer">
    <div class="footer-container">
        <div class="footer-section">
            <h3>Contact Us</h3>
            <p>Email: contact@speechtherapy.com</p>
            <p>Phone: +000000</p>
            <p>Address: RCCIIT, KOLKATA</p>
        </div>
        <div class="footer-section">
            <h3>Quick Links</h3>
            <ul>
                <li><a href="#">Home</a></li>
                <li><a href="#">About Us</a></li>
                <li><a href="#">Services</a></li>
                <li><a href="#">Privacy Policy</a></li>
            </ul>
        </div>
        <div class="footer-section">
            <h3>Follow Us</h3>
            <div class="social-icons">
                <a href="#"><img src="facebook-icon.png" alt="Facebook"></a>
                <a href="#"><img src="twitter-icon.jpeg" alt="Twitter"></a>
                <a href="#"><img src="linkedin-icon.jpeg" alt="LinkedIn"></a>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; 2024 Speech Therapy Clinic | All Rights Reserved</p>
    </div>
</footer>
<script src="https://cdn.botpress.cloud/webchat/v1/inject.js"></script>
<script src="https://mediafiles.botpress.cloud/f97b401b-aec4-4ca8-8890-5ea3bd43a4ba/webchat/config.js" defer></script>


    
    
    
    <!-- foter  -->
</body>
</html>