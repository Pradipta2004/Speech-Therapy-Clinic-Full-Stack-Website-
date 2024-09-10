<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "speech_therapy_clinic_approved");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all approved therapists
$sql = "SELECT fullname, education, rating FROM approved_therapists";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Approved Therapists Catalog</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .catalog {
            display: flex;
            flex-wrap: wrap;
        }
        .therapist {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            margin: 10px;
            width: calc(33% - 40px);
            box-sizing: border-box;
            text-align: center;
        }
        .therapist img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 10px;
        }
        .therapist h3 {
            margin: 0;
        }
        .therapist p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <h1>Approved Therapists Catalog</h1>
    <div class="catalog">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='therapist'>";
                // Space for therapist image
                echo "<img src='path_to_default_image.jpg' alt='Therapist Image'>";
                echo "<h3>" . htmlspecialchars($row['fullname']) . "</h3>";
                echo "<p><strong>Education:</strong> " . htmlspecialchars($row['education']) . "</p>";
                echo "<p><strong>Rating:</strong> " . htmlspecialchars($row['rating']) . "</p>";
                echo "</div>";
            }
        } else {
            echo "<p>No approved therapists found.</p>";
        }

        $conn->close();
        ?>
    </div>
</body>
</html>
