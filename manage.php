<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "speech_therapy_clinic_approved");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Add new therapist
if (isset($_POST['add_therapist'])) {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $license = $_POST['license'];
    $sql = "INSERT INTO approved_therapists (fullname, email, license) VALUES ('$fullname', '$email', '$license')";
    if ($conn->query($sql) === TRUE) {
        echo "New therapist added successfully.<br>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Update therapist
if (isset($_POST['update_therapist'])) {
    $id = $_POST['id'];
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $license = $_POST['license'];
    $sql = "UPDATE approved_therapists SET fullname='$fullname', email='$email', license='$license' WHERE id='$id'";
    if ($conn->query($sql) === TRUE) {
        echo "Therapist updated successfully.<br>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Delete therapist
if (isset($_POST['delete_therapist'])) {
    $id = $_POST['id'];
    $sql = "DELETE FROM approved_therapists WHERE id='$id'";
    if ($conn->query($sql) === TRUE) {
        echo "Therapist deleted successfully.<br>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Fetch therapists
$sql = "SELECT * FROM approved_therapists";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Approved Therapists</title>
    <!-- External CSS Links -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* General Styling */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 20px;
            color: #333;
        }

        h2, h3 {
            color: #4a90e2;
            text-transform: uppercase;
            font-weight: 600;
            margin-bottom: 20px;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
        }

        form {
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 40px;
            transition: box-shadow 0.3s ease;
        }

        form:hover {
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.2);
        }

        label {
            display: block;
            font-weight: 500;
            color: #555;
            margin: 10px 0 5px;
        }

        input[type="text"], input[type="email"], input[type="date"], input[type="time"] {
            width: calc(100% - 20px);
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
            margin-bottom: 15px;
            font-size: 14px;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        input[type="text"]:focus, input[type="email"]:focus, input[type="date"]:focus, input[type="time"]:focus {
            border-color: #4a90e2;
            outline: none;
            box-shadow: 0 0 8px rgba(74, 144, 226, 0.3);
        }

        button {
            background: linear-gradient(135deg, #4a90e2, #0056b3);
            color: #ffffff;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 14px;
            transition: background 0.3s ease, transform 0.3s ease;
        }

        button:hover {
            background: linear-gradient(135deg, #0056b3, #003d7a);
            transform: scale(1.02);
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-bottom: 40px;
        }

        table thead {
            background: #4a90e2;
            color: #ffffff;
            text-transform: uppercase;
            font-weight: 600;
        }

        table th, table td {
            padding: 12px 15px;
            text-align: left;
            font-size: 14px;
            color: #333;
        }

        table th {
            background: #4a90e2;
            color: #ffffff;
        }

        table tr {
            border-bottom: 1px solid #ddd;
        }

        table tr:nth-of-type(even) {
            background-color: #f9f9f9;
        }

        table tr:hover {
            background-color: #f0f5ff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Action Buttons Styling */
        td form {
            display: flex;
            gap: 10px;
        }

        td form input[type="text"], td form input[type="email"] {
            width: calc(100% - 30px);
            padding: 6px;
            border-radius: 4px;
            border: 1px solid #ddd;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        td form button[type="submit"] {
            background: #4a90e2;
            color: #ffffff;
            border: none;
            border-radius: 4px;
            padding: 8px 16px;
            cursor: pointer;
            font-size: 12px;
            transition: background 0.3s ease, transform 0.3s ease;
        }

        td form button.update {
            background: #32c88a;
        }

        td form button.delete {
            background: #e94f4f;
        }

        td form button:hover.update {
            background: #29a974;
            transform: scale(1.02);
        }

        td form button:hover.delete {
            background: #d23f3f;
            transform: scale(1.02);
        }

        /* Input and Textarea Styling */
        textarea {
            height: 80px;
            resize: vertical;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        textarea:focus {
            border-color: #4a90e2;
            box-shadow: 0 0 8px rgba(74, 144, 226, 0.3);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            form {
                padding: 15px;
            }

            table th, table td {
                padding: 10px 12px;
                font-size: 12px;
            }

            button {
                font-size: 12px;
                padding: 8px 16px;
            }
        }

        /* Advanced Styling */
        .shadow-effect {
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
        }

        .rounded {
            border-radius: 8px;
        }

        table th, table td {
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        table th {
            background: #007bff;
        }

        table tr:hover {
            background-color: #eaf4ff;
        }
    </style></head>
<body>
    <h2>Manage Approved Therapists</h2>

    <!-- Add Therapist Form -->
    <h3>Add New Therapist</h3>
    <form action="" method="POST">
        <label for="fullname">Full Name:</label>
        <input type="text" id="fullname" name="fullname" required>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <label for="license">License Number:</label>
        <input type="text" id="license" name="license" required>
        <button type="submit" name="add_therapist">Add Therapist</button>
    </form>

    <!-- Therapists Table -->
    <h3>Approved Therapists</h3>
    <?php
    if ($result->num_rows > 0) {
        echo "<table>";
        echo "<tr><th>ID</th><th>Full Name</th><th>Email</th><th>License Number</th><th>Actions</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['id']) . "</td>";
            echo "<td>" . htmlspecialchars($row['fullname']) . "</td>";
            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
            echo "<td>" . htmlspecialchars($row['license']) . "</td>";
            echo "<td>";
            echo "<form action='' method='POST' style='display:inline;'>
                    <input type='hidden' name='id' value='" . $row['id'] . "'>
                    <input type='text' name='fullname' value='" . htmlspecialchars($row['fullname']) . "' required>
                    <input type='email' name='email' value='" . htmlspecialchars($row['email']) . "' required>
                    <input type='text' name='license' value='" . htmlspecialchars($row['license']) . "' required>
                    <button type='submit' name='update_therapist'>Update</button>
                  </form>";
            echo " | ";
            echo "<form action='' method='POST' style='display:inline;'>
                    <input type='hidden' name='id' value='" . $row['id'] . "'>
                    <button type='submit' name='delete_therapist'>Delete</button>
                  </form>";
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "No approved therapists found.";
    }

    $conn->close();
    ?>
</body>
</html>
