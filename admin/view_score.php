<?php
session_start();
include '../db.php'; // Adjusted path for DB file

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

// Get student ID from URL
if (isset($_GET['user_id']) && is_numeric($_GET['user_id'])) {
    $user_id = intval($_GET['user_id']);

    // Fetch the student's score
    $stmt = $conn->prepare("SELECT score FROM results WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($score);
    if ($stmt->fetch()) {
        $stmt->close();
    } else {
        $stmt->close();
        echo "<p style='color: red; text-align: center;'>No score found for the given user ID.</p>";
        exit();
    }
} else {
    echo "<p style='color: red; text-align: center;'>Invalid user ID.</p>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Score</title>
    <link rel="stylesheet" href="../styles.css">
    <style>
        /* Custom styles for light UI */
        body {
            font-family: 'Arial', sans-serif;
            background: #f9f9f9;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .container {
            max-width: 600px;
            width: 90%;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
            border: 1px solid #ddd;
        }

        h1 {
            font-size: 2rem;
            margin-bottom: 1rem;
            color: #444;
        }

        p {
            font-size: 1rem;
            margin-bottom: 1rem;
        }

        .details strong {
            color: #007bff;
        }

        a {
            display: inline-block;
            text-decoration: none;
            background: #007bff;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: bold;
            transition: background 0.3s ease, transform 0.2s ease;
        }

        a:hover {
            background: #0056b3;
            transform: scale(1.05);
        }

        .back-button {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Student Score</h1>
        <div class="details">
            <p><strong>Student ID:</strong> <?php echo htmlspecialchars($user_id, ENT_QUOTES, 'UTF-8'); ?></p>
            <p><strong>Score:</strong> <?php echo htmlspecialchars($score, ENT_QUOTES, 'UTF-8'); ?></p>
        </div>
        <div class="back-button">
            <a href="../admin_dashboard.php">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>
