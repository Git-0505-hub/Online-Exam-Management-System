<?php
session_start();
include 'db.php';

// Check if the user is logged in and is a student
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    
    <!-- Link to the CSS file -->
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<header>
    <h1>Welcome to Your Dashboard</h1>
</header>
<br>
<div class="container">
    <div class="dashboard-content">
        <h2>Hello, <?php echo $_SESSION['username']; ?>!</h2>

        <div class="dashboard-links">
            <a class="btn-link" href="exam.php">Take Exam</a>
            <a class="btn-link" href="results.php">View My Results</a>
            <a class="btn-link" href="logout.php">Logout</a>
        </div>
    </div>
</div>

<footer>
    <p>&copy; 2024 Online Examination System. All Rights Reserved.</p>
</footer>

</body>
</html>
