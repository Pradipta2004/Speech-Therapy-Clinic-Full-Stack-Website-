<?php
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "", "speech_therapy_clinic_approved");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $conn->real_escape_string($_POST['password']);

    // Query to check if the therapist exists in the approved_therapists table
    $sql = "SELECT * FROM approved_therapists WHERE email='$email' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['email'] = $user['email'];
        $_SESSION['userType'] = 'therapist'; // Storing user type in session
        header("Location: approved_dashboard.php");
        exit();
    } else {
        echo "Invalid email or password";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Therapist Login</title>
    <style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Arial', sans-serif;
}

body {
    background: linear-gradient(135deg, #74ebd5, #ACB6E5);
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
}

h2 {
    text-align: center;
    font-size: 2rem;
    color: #fff;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
    margin-bottom: 20px;
}

form {
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(10px);
    padding: 40px;
    border-radius: 15px;
    box-shadow: 0 15px 25px rgba(0, 0, 0, 0.5);
    width: 300px;
    text-align: center;
    transition: transform 0.3s ease-in-out;
}

form:hover {
    transform: scale(1.05);
}

label {
    color: #fff;
    font-size: 1.1rem;
    margin-bottom: 10px;
    display: inline-block;
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
}

input[type="email"],
input[type="password"] {
    width: 100%;
    padding: 12px;
    margin: 10px 0;
    border: none;
    border-radius: 5px;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
    outline: none;
    transition: all 0.3s ease;
    background: rgba(255, 255, 255, 0.8);
    font-size: 1rem;
}

input[type="email"]:focus,
input[type="password"]:focus {
    box-shadow: 0 0 15px rgba(50, 150, 255, 0.7);
    border: 1px solid #327ab7;
}

button {
    background: linear-gradient(45deg, #6a82fb, #fc5c7d);
    border: none;
    color: #fff;
    padding: 12px 20px;
    font-size: 1rem;
    cursor: pointer;
    border-radius: 30px;
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
    transition: all 0.3s ease-in-out;
}

button:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 25px rgba(0, 0, 0, 0.4);
}

button:active {
    transform: translateY(-1px);
    box-shadow: 0 8px 12px rgba(0, 0, 0, 0.2);
}

form::before {
    content: "";
    position: absolute;
    top: -10px;
    left: -10px;
    right: -10px;
    bottom: -10px;
    background: linear-gradient(45deg, #74ebd5, #ACB6E5);
    z-index: -1;
    border-radius: 15px;
    filter: blur(15px);
    opacity: 0.8;
}

form:hover::before {
    opacity: 1;
}

@media screen and (max-width: 600px) {
    form {
        width: 90%;
    }
}





        </style>
</head>
<body>
    <h2>Therapist Login</h2>
    <form method="POST" action="login_approval.php">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <br><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <br><br>
        <button type="submit">Login</button>
    </form>
</body>
</html>
