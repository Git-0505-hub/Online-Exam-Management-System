<?php
session_start();
include 'db.php';

// Ensure that the exam is being submitted by a valid user
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Get the start time from the session
$start_time = $_SESSION['start_time'] ?? time();  // Set start time if not already set in session
$end_time = time(); // Get the current time when the exam is submitted
$time_taken = $end_time - $start_time; // Time taken in seconds

// Calculate the score based on user input
$score = 0;
foreach ($_POST as $key => $value) {
    $question_id = str_replace("answer_", "", $key);
    $correct = $conn->query("SELECT correct_option FROM questions WHERE question_id = $question_id")->fetch_assoc();
    if ($value == $correct['correct_option']) {
        $score++;
    }
}

// Get the user ID and exam ID
$user_id = $_SESSION['user_id'];
$exam_id = 1; // Static for simplicity (You can dynamically set the exam ID if necessary)

// Store the result, including time taken and user score, in the database
$conn->query("INSERT INTO results (user_id, exam_id, score, start_time, end_time) 
              VALUES ($user_id, $exam_id, $score, FROM_UNIXTIME($start_time), FROM_UNIXTIME($end_time))");

// Determine a compliment based on the score
$compliment = '';
if ($score >= 80) {
    $compliment = "Excellent! You did a fantastic job!";
} elseif ($score >= 60) {
    $compliment = "Good job! You performed well.";
} elseif ($score >= 40) {
    $compliment = "Nice effort! But you can do better.";
} else {
    $compliment = "Don't worry, keep practicing and you'll improve!";
}

// Format time taken in minutes and seconds
$minutes = floor($time_taken / 60);
$seconds = $time_taken % 60;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Results</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .result-container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 50%;
        }

        .result-container h2 {
            color: #333;
        }

        .result-container .score {
            font-size: 2em;
            color: #4caf50;
        }

        .result-container .time {
            font-size: 1.2em;
            margin: 20px 0;
        }

        .result-container .compliment {
            font-size: 1.2em;
            font-weight: bold;
            margin: 20px 0;
        }

        .result-container .btn {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
        }

        .result-container .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="result-container">
    <h2>Exam Results</h2>
    
    <p class="score">Your Score: <?= $score ?> / <?= count($_POST) ?></p>
    
    <p class="time">Time Taken: <?= $minutes ?> minutes and <?= $seconds ?> seconds</p>
    
    <p class="compliment"><?= $compliment ?></p>

    <a href="user_dashboard.php" class="btn">Back to Dashboard</a>
</div>

</body>
</html>
