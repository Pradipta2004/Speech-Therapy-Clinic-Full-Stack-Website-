<?php
session_start();
if ($_SESSION['userType'] != 'supervisor') {  // Assuming only admin can manage therapists
    header("Location: login.html");
    exit();
}

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
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        form {
            margin-bottom: 20px;
        }
    </style>
</head>
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
