<?php
session_start();
if ($_SESSION['userType'] != 'therapist') {
    header("Location: login_approval.php");
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "", "speech_therapy_clinic_approved");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = $_SESSION['email'];
$sql = "SELECT * FROM approved_therapists WHERE email='$email'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Approved Therapist Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            transition: background-color 0.5s, color 0.5s;
        }
        .navbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #333;
            padding: 10px;
            position: fixed;
            width: 100%;
            top: 0;
            left: 0;
            z-index: 1000;
        }
        .navbar .menu-button {
            background-color: transparent;
            border: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
            margin-right: 15px; /* Added margin to separate from the heading */
        }
        .navbar .heading {
            color: white;
            font-size: 20px;
            margin-right: auto; /* Pushes the search bar to the right */
        }
        .navbar .search-bar {
            flex: 1;
            margin: 0 200px;  /*  15 */
            padding: 5px;
            border-radius: 20px;   /* 5*/
            border: 1px solid #ccc;
            font-size: 16px;
        }
        .navbar .toggle-container {
            position: relative;
            width: 50px;
            height: 25px;
            background-color: #bbb;
            border-radius: 25px;
            margin-left: 20px;
            cursor: pointer;
            transition: background-color 0.5s;
        }
        .navbar .toggle-container .toggle-button {
            position: absolute;
            width: 23px;
            height: 23px;
            background-color: white;
            border-radius: 50%;
            top: 1px;
            left: 1px;
            transition: transform 0.5s;
        }
        .dark-mode .navbar .toggle-container {
            background-color: #666;
        }
        .dark-mode .navbar .toggle-container .toggle-button {
            transform: translateX(25px);
        }
        .navbar .profile-button, .navbar .notification-button {
            background-color: orange;
            color: white;
            border: none;
            cursor: pointer;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 16px;
            margin-right: 10px;
            position: relative;
        }
        .navbar .profile-button img {
            width: 24px;
            height: 24px;
        }
        .dropdown-content, .notification-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
            top: 40px;
            right: 0;
            min-width: 180px;
            border-radius: 5px;
            overflow: hidden;
        }
        .dropdown-content {
            width: 250px; /* Reduced width for profile dropdown */
        }
        .dropdown-content a, .notification-content p {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }
        .dropdown-content a:hover, .notification-content p:hover {
            background-color: #f1f1f1;
        }
        .navbar .logout-button {
            background-color: #f44336;
            color: white;
            border: none;
            padding: 10px 20px;   /*  10 20  */
            cursor: pointer;
            border-radius: 5px;
            margin-left: 20px;
        }
        .menu-content {
            display: none;
            position: fixed;
            background-color: rgba(0,0,0,0.5); /* Transparent background */
            height: 100%;
            width: 250px;
            top: 0;
            left: 0;
            z-index: 2000;
            padding-top: 60px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.3);
            transition: 0.5s;
        }
        .menu-content a {
            padding: 10px 20px;
            text-decoration: none;
            font-size: 22px;
            color: white;
            display: block;
            transition: 0.3s;
        }
        .menu-content a:hover {
            background-color: #575757;
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
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 50%; /* Reduced width for profile modal */
            border-radius: 10px;
            box-shadow: 0px 0px 15px rgba(0,0,0,0.3);
            transform: rotateX(10deg);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .modal-content:hover {
            transform: rotateX(0deg);
            box-shadow: 0px 0px 25px rgba(0,0,0,0.5);
        }
        .modal-content h2 {
            color: #333;
            margin-bottom: 20px;
        }
        .modal-content p {
            margin: 10px 0;
            font-size: 16px;
            line-height: 1.5;
            color: #555;
        }
        .modal-content i.pencil-icon {
            color: #333;
            font-size: 14px;
            margin-left: 10px;
        }
        #scheduleButton {
            background-color: #ff9800;
            color: white;
            padding: 30px 60px; /* 2X larger */
            border: none;
            border-radius: 5px;
            cursor: pointer;
            position: fixed;
            bottom: 20px;
            left: 20px; /* Moved to the left lower portion */
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            font-size: 24px; /* Increased size */
        }
        .background-image {
            background-image: url('your-background-image.jpg');
            background-size: cover;
            background-position: center;
            height: 100vh;
            opacity: 0.9;
        }

        /* starts here */
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
/* ends here */




    </style>
    <script>
        function toggleTheme() {
            document.body.classList.toggle("dark-mode");
        }
        function showModal() {
            document.getElementById("profileModal").style.display = "block";
        }
        function closeModal() {
            document.getElementById("profileModal").style.display = "none";
        }
        function showMenu() {
            const menu = document.getElementById("menuContent");
            menu.style.display = menu.style.display === "block" ? "none" : "block";
        }
        function showNotifications() {
            const notifications = document.getElementById("notificationContent");
            notifications.style.display = notifications.style.display === "block" ? "none" : "block";
        }
        window.onclick = function(event) {
            if (event.target == document.getElementById("profileModal")) {
                document.getElementById("profileModal").style.display = "none";
            }
            if (!event.target.matches('.notification-button')) {
                document.getElementById("notificationContent").style.display = "none";
            }
            if (!event.target.matches('.menu-button')) {
                document.getElementById("menuContent").style.display = "none";
            }
        }
    </script>
</head>
<body>
    <div class="navbar">
        <button class="menu-button" onclick="showMenu()">&#9776;</button>
        <div class="heading">Therapist Dashboard</div>
        <input type="text" class="search-bar" placeholder="Search...">
        <button class="notification-button" onclick="showNotifications()">P</button>
        <button class="profile-button" onclick="showModal()">
            <img src="profile-icon.png" alt="Profile">
        </button>
        <div class="toggle-container" onclick="toggleTheme()">
            <div class="toggle-button"></div>
        </div>
        <button class="logout-button" onclick="location.href='logout.php'">Logout</button>
    </div>

    <div class="background-image"></div>

    <!-- <h2>Welcome, Approved Therapist</h2>
    <p>Your email: <?php echo htmlspecialchars($user['email']); ?></p> -->

    <button id="scheduleButton" onclick="openSchedules()">Schedules</button>

    <div id="profileModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Profile Details</h2>
            <p><strong>Full Name:</strong> <?php echo htmlspecialchars($user['fullname']); ?> <i class="pencil-icon">✎</i></p>
            <p><strong>Date of Birth:</strong> <?php echo htmlspecialchars($user['dob']); ?> <i class="pencil-icon">✎</i></p>
            <p><strong>Gender:</strong> <?php echo htmlspecialchars($user['gender']); ?> <i class="pencil-icon">✎</i></p>
            <p><strong>Contact No.:</strong> <?php echo htmlspecialchars($user['contact']); ?> <i class="pencil-icon">✎</i></p>
            <p><strong>Professional License Number:</strong> <?php echo htmlspecialchars($user['license']); ?> <i class="pencil-icon">✎</i></p>
            <p><strong>Educational Background:</strong> <?php echo htmlspecialchars($user['education']); ?> <i class="pencil-icon">✎</i></p>
            <p><strong>Years of Experience:</strong> <?php echo htmlspecialchars($user['experience']); ?> <i class="pencil-icon">✎</i></p>
            <p><strong>Specializations:</strong> <?php echo htmlspecialchars($user['specialization']); ?> <i class="pencil-icon">✎</i></p>
            <p><strong>Email ID:</strong> <?php echo htmlspecialchars($user['email']); ?> <i class="pencil-icon">✎</i></p>
        </div>
    </div>

    <div id="notificationContent" class="notification-content">
        <p>A patient’s appointment is scheduled on [Date] at [Time]</p>
        <p>A patient’s appointment is scheduled on [Date] at [Time]</p>
        <p>A patient’s appointment is scheduled on [Date] at [Time]</p>
    </div>

    <div id="menuContent" class="menu-content">
        <a href="#">Home</a>
        <a href="#">Settings</a>
        <a href="#">Help</a>
        <a href="#">Logout</a>
    </div>
        <!-- Content Below Background Image -->
        <div class="below-background">
        <!-- Image and About Section -->
        <div class="image-about-container">
            <div class="image-section">
                <img src="your-image.jpeg" alt="About Us Image">
            </div>
            <div class="about-section">
                <h2>About Us</h2>
                <p>Welcome to our therapy clinic. We provide top-notch therapy services tailored to your needs. Our team of skilled therapists is dedicated to helping you achieve your goals and improve your quality of life. Discover more about our services and team below.</p>
            </div>
        </div>

        <!-- Catalogs Section -->
        <div class="catalog-container">
            <div class="catalog">
                <img src="catalog-logo1.jpg" alt="Catalog 1 Logo"><a href="patient-analytics.html">
                <div class="catalog-content">Patient Analytics</div>
            </div>
            <div class="catalog"><a href="therapy_plan_generator.html">
                <img src="catalog-logo2.jpg" alt="Catalog 2 Logo">
                <div class="catalog-content">AI Report Genarator</div>
            </div>
            <div class="catalog"><a href="virtual_session.html">
                <img src="catalog-logo3.jpeg" alt="Catalog 3 Logo">
                <div class="catalog-content">Virtual Therapy Session</div>
            </div>
            <div class="catalog"><a href="#">
                <img src="catalog-logo4.jpg" alt="Catalog 4 Logo">
             <div class="catalog-content">Voice Recognition BOT</div>
    </a>
            </div>
            <div class="catalog">
                <img src="catalog-logo5.png" alt="Catalog 5 Logo">
                <div class="catalog-content">Collaborative Tools</div>
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
            <p>Phone: +000 0000 0000</p>
            <p>Address: 123 Therapy Lane, Wellness City, Tx</p>
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

    
    
    
    <!-- foter  -->


    <script>
        function openSchedules() {
            window.open('check_schedule.php?therapist_name=<?php echo urlencode($user['fullname']); ?>', 'popupWindow', 'width=800,height=600');
        }
        function openReport() {
            window.open('generate_report.php?therapist_name=<?php echo urlencode($user['fullname']); ?>', 'popupWindow', 'width=800,height=600');
        }
    </script>
    <script src="https://cdn.botpress.cloud/webchat/v1/inject.js"></script>
<script src="https://mediafiles.botpress.cloud/f97b401b-aec4-4ca8-8890-5ea3bd43a4ba/webchat/config.js" defer></script>

</body>
</html>