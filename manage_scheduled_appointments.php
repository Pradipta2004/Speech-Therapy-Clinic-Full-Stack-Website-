<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "speech_therapy_clinic_scheduled_appointment");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Add new appointment
if (isset($_POST['add_appointment'])) {
    $pending_appointment_id = $_POST['pending_appointment_id'];
    $username = $_POST['username'];
    $patient_name = $_POST['patient_name'];
    $patient_phone = $_POST['patient_phone'];
    $preferred_date = $_POST['preferred_date'];
    $preferred_time = $_POST['preferred_time'];
    $disease = $_POST['disease'];
    $about = $_POST['about'];
    $therapist_name = $_POST['therapist_name'];
    $scheduled_date = $_POST['scheduled_date'];
    $scheduled_time = $_POST['scheduled_time'];
    
    $sql = "INSERT INTO scheduled_appointments 
            (pending_appointment_id, username, patient_name, patient_phone, preferred_date, preferred_time, disease, about, therapist_name, scheduled_date, scheduled_time)
            VALUES ('$pending_appointment_id', '$username', '$patient_name', '$patient_phone', '$preferred_date', '$preferred_time', '$disease', '$about', '$therapist_name', '$scheduled_date', '$scheduled_time')";
    
    if ($conn->query($sql) === TRUE) {
        echo "New appointment added successfully.<br>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Update appointment
if (isset($_POST['update_appointment'])) {
    $id = $_POST['id'];
    $pending_appointment_id = $_POST['pending_appointment_id'];
    $username = $_POST['username'];
    $patient_name = $_POST['patient_name'];
    $patient_phone = $_POST['patient_phone'];
    $preferred_date = $_POST['preferred_date'];
    $preferred_time = $_POST['preferred_time'];
    $disease = $_POST['disease'];
    $about = $_POST['about'];
    $therapist_name = $_POST['therapist_name'];
    $scheduled_date = $_POST['scheduled_date'];
    $scheduled_time = $_POST['scheduled_time'];
    
    $sql = "UPDATE scheduled_appointments 
            SET pending_appointment_id='$pending_appointment_id', username='$username', patient_name='$patient_name', patient_phone='$patient_phone', 
                preferred_date='$preferred_date', preferred_time='$preferred_time', disease='$disease', about='$about', 
                therapist_name='$therapist_name', scheduled_date='$scheduled_date', scheduled_time='$scheduled_time'
            WHERE id='$id'";
    
    if ($conn->query($sql) === TRUE) {
        echo "Appointment updated successfully.<br>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Delete appointment
if (isset($_POST['delete_appointment'])) {
    $id = $_POST['id'];
    $sql = "DELETE FROM scheduled_appointments WHERE id='$id'";
    
    if ($conn->query($sql) === TRUE) {
        echo "Appointment deleted successfully.<br>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Fetch appointments
$sql = "SELECT * FROM scheduled_appointments";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Scheduled Appointments</title>
    <style>
      <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Scheduled Appointments</title>
    <!-- External CSS Links -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        /* General Styling */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding: 20px;
            color: #333;
        }

        h2, h3 {
            color: #4A90E2;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
        }

        form {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 40px;
            transition: transform 0.3s ease-in-out;
        }

        form:hover {
            transform: translateY(-5px);
        }

        label {
            display: block;
            font-weight: 500;
            color: #555;
            margin: 10px 0 5px;
        }

        input[type="text"], input[type="date"], input[type="time"], textarea {
            width: calc(100% - 20px);
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
            margin-bottom: 15px;
            font-size: 14px;
        }

        input[type="text"]:focus, input[type="date"]:focus, input[type="time"]:focus, textarea:focus {
            border-color: #4A90E2;
            outline: none;
            box-shadow: 0 0 8px rgba(74, 144, 226, 0.5);
        }

        button {
            background: linear-gradient(135deg, #6B73FF 0%, #000DFF 100%);
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 14px;
            transition: background 0.3s ease-in-out;
        }

        button:hover {
            background: linear-gradient(135deg, #5a63d9 0%, #000aff 100%);
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 40px;
        }

        table thead {
            background: #4A90E2;
            color: #fff;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        table th, table td {
            padding: 12px 15px;
            text-align: left;
            font-size: 14px;
        }

        table th {
            background: #4A90E2;
            color: #fff;
            font-weight: 600;
        }

        table tr {
            border-bottom: 1px solid #ddd;
        }

        table tr:nth-of-type(even) {
            background-color: #f4f7fc;
        }

        table tr:hover {
            background-color: #f1f5ff;
        }

        /* Action Buttons Styling */
        td form {
            display: flex;
            gap: 5px;
        }

        td form input[type="text"], td form textarea {
            width: 100%;
            padding: 5px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        td form button[type="submit"] {
            background: #4A90E2;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 6px 12px;
            cursor: pointer;
            font-size: 12px;
            transition: background 0.3s ease-in-out;
        }

        td form button.update {
            background: #32C88A;
        }

        td form button.delete {
            background: #FF5A5F;
        }

        td form button:hover.update {
            background: #29a974;
        }

        td form button:hover.delete {
            background: #d14e54;
        }

        /* Input Styling */
        textarea {
            height: 80px;
        }

        input[type="text"], input[type="date"], input[type="time"], textarea {
            background-color: #f9f9f9;
            transition: border-color 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        }

        input[type="text"]:focus, input[type="date"]:focus, input[type="time"]:focus, textarea:focus {
            box-shadow: 0 0 12px rgba(0, 125, 255, 0.2);
        }

        /* Responsiveness */
        @media (max-width: 768px) {
            form {
                padding: 15px;
            }

            table th, table td {
                padding: 8px 10px;
                font-size: 12px;
            }

            button {
                font-size: 12px;
                padding: 8px 16px;
            }
        }

        /* Extra Styling for Professional Look */
        .shadow-effect {
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .rounded {
            border-radius: 8px;
        }

        /* Adding hover effect for interactivity */
        table tr:hover {
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            background-color: #f1f5ff;
            transition: all 0.3s ease-in-out;
        }
    </style>
</head>
<body>
    <h2>Manage Scheduled Appointments</h2>

    <!-- Add Appointment Form -->
    <h3>Add New Appointment</h3>
    <form action="" method="POST">
        <label for="pending_appointment_id">Pending Appointment ID:</label>
        <input type="text" id="pending_appointment_id" name="pending_appointment_id" required>
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <label for="patient_name">Patient Name:</label>
        <input type="text" id="patient_name" name="patient_name" required>
        <label for="patient_phone">Patient Phone:</label>
        <input type="text" id="patient_phone" name="patient_phone" required>
        <label for="preferred_date">Preferred Date:</label>
        <input type="date" id="preferred_date" name="preferred_date" required>
        <label for="preferred_time">Preferred Time:</label>
        <input type="time" id="preferred_time" name="preferred_time" required>
        <label for="disease">Disease:</label>
        <input type="text" id="disease" name="disease" required>
        <label for="about">About:</label>
        <textarea id="about" name="about" required></textarea>
        <label for="therapist_name">Therapist Name:</label>
        <input type="text" id="therapist_name" name="therapist_name" required>
        <label for="scheduled_date">Scheduled Date:</label>
        <input type="date" id="scheduled_date" name="scheduled_date" required>
        <label for="scheduled_time">Scheduled Time:</label>
        <input type="time" id="scheduled_time" name="scheduled_time" required>
        <button type="submit" name="add_appointment">Add Appointment</button>
    </form>

    <!-- Appointments Table -->
    <h3>Scheduled Appointments</h3>
    <?php
    if ($result->num_rows > 0) {
        echo "<table>";
        echo "<tr><th>ID</th><th>Pending Appointment ID</th><th>Username</th><th>Patient Name</th><th>Patient Phone</th><th>Preferred Date</th><th>Preferred Time</th><th>Disease</th><th>About</th><th>Therapist Name</th><th>Scheduled Date</th><th>Scheduled Time</th><th>Actions</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['id']) . "</td>";
            echo "<td>" . htmlspecialchars($row['pending_appointment_id']) . "</td>";
            echo "<td>" . htmlspecialchars($row['username']) . "</td>";
            echo "<td>" . htmlspecialchars($row['patient_name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['patient_phone']) . "</td>";
            echo "<td>" . htmlspecialchars($row['preferred_date']) . "</td>";
            echo "<td>" . htmlspecialchars($row['preferred_time']) . "</td>";
            echo "<td>" . htmlspecialchars($row['disease']) . "</td>";
            echo "<td>" . htmlspecialchars($row['about']) . "</td>";
            echo "<td>" . htmlspecialchars($row['therapist_name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['scheduled_date']) . "</td>";
            echo "<td>" . htmlspecialchars($row['scheduled_time']) . "</td>";
            echo "<td>";
            echo "<form action='' method='POST' style='display:inline;'>
                    <input type='hidden' name='id' value='" . $row['id'] . "'>
                    <input type='text' name='pending_appointment_id' value='" . htmlspecialchars($row['pending_appointment_id']) . "' required>
                    <input type='text' name='username' value='" . htmlspecialchars($row['username']) . "' required>
                    <input type='text' name='patient_name' value='" . htmlspecialchars($row['patient_name']) . "' required>
                    <input type='text' name='patient_phone' value='" . htmlspecialchars($row['patient_phone']) . "' required>
                    <input type='date' name='preferred_date' value='" . htmlspecialchars($row['preferred_date']) . "' required>
                    <input type='time' name='preferred_time' value='" . htmlspecialchars($row['preferred_time']) . "' required>
                    <input type='text' name='disease' value='" . htmlspecialchars($row['disease']) . "' required>
                    <textarea name='about' required>" . htmlspecialchars($row['about']) . "</textarea>
                    <input type='text' name='therapist_name' value='" . htmlspecialchars($row['therapist_name']) . "' required>
                    <input type='date' name='scheduled_date' value='" . htmlspecialchars($row['scheduled_date']) . "' required>
                    <input type='time' name='scheduled_time' value='" . htmlspecialchars($row['scheduled_time']) . "' required>
                    <button type='submit' name='update_appointment'>Update</button>
                  </form>";
            echo " | ";
            echo "<form action='' method='POST' style='display:inline;'>
                    <input type='hidden' name='id' value='" . $row['id'] . "'>
                    <button type='submit' name='delete_appointment'>Delete</button>
                  </form>";
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "No scheduled appointments found.";
    }

    $conn->close();
    ?>
</body>
</html>
