<?php
include 'db.php';

$exam_id = 1; // Static for simplicity

$result = $conn->query("SELECT * FROM questions");
echo '<form method="POST" action="submit_exam.php">';
while ($question = $result->fetch_assoc()) {
    echo '<p>' . $question['question_text'] . '</p>';
    echo '<input type="radio" name="answer_' . $question['question_id'] . '" value="A"> ' . $question['option_a'];
    // Repeat for options B, C, D
}
echo '<button type="submit">Submit Exam</button></form>';
?>
