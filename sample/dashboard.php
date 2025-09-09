<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

echo "<h2>Welcome to the Online Exam System, " . ($_SESSION['role'] == 'admin' ? "Admin" : "Student") . "</h2>";

if ($_SESSION['role'] == 'admin') {
    // Admin Dashboard
    echo "<a href='results.php'>View All Results</a>";
    echo "<h3>Add New Question</h3>";
    echo '<form method="POST" action="">
            <input type="text" name="question_text" placeholder="Question" required>
            <input type="text" name="option_a" placeholder="Option A" required>
            <input type="text" name="option_b" placeholder="Option B" required>
            <input type="text" name="option_c" placeholder="Option C" required>
            <input type="text" name="option_d" placeholder="Option D" required>
            <input type="text" name="correct_option" placeholder="Correct Option (A/B/C/D)" required>
            <button type="submit" name="add_question">Add Question</button>
          </form>';
    
    if (isset($_POST['add_question'])) {
        $question_text = $_POST['question_text'];
        $option_a = $_POST['option_a'];
        $option_b = $_POST['option_b'];
        $option_c = $_POST['option_c'];
        $option_d = $_POST['option_d'];
        $correct_option = $_POST['correct_option'];

        $conn->query("INSERT INTO questions (question_text, option_a, option_b, option_c, option_d, correct_option) 
                      VALUES ('$question_text', '$option_a', '$option_b', '$option_c', '$option_d', '$correct_option')");
        echo "Question added successfully!";
    }
} else {
    // Student Dashboard
    echo "<a href='exam.php'>Take Exam</a>";
    echo "<a href='results.php'>View My Results</a>";
}
?>
