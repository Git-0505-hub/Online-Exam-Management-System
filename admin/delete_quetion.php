<?php
session_start();
include '../db.php'; // Adjusted path for DB file

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

// Get question ID from URL and validate it
if (isset($_GET['question_id'])) {
    $question_id = $_GET['question_id'];

    // Prepare and execute the SQL statement to delete the question
    $stmt = $conn->prepare("DELETE FROM questions WHERE question_id = ?");
    $stmt->bind_param("i", $question_id);

    if ($stmt->execute()) {
        // Successfully deleted, redirect to admin dashboard with success message
        $_SESSION['message'] = "Question deleted successfully!";
    } else {
        // Error during deletion, set error message
        $_SESSION['message'] = "Error deleting question: " . $stmt->error;
    }
    
    $stmt->close();

    // Redirect back to admin dashboard
    header("Location: ../admin_dashboard.php");
    exit();
} else {
    echo "Invalid question ID.";
    exit();
}
