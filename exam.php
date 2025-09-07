<?php
session_start();
include 'db.php';

// Ensure the user is logged in and the role is 'student'
if ($_SESSION['role'] != 'student') {
    header("Location: dashboard.php");
    exit();
}

// Set the start time of the exam when the page is opened
$_SESSION['start_time'] = time(); // Save the start time when the exam page is accessed

$exam_id = 1; // Example exam ID for simplicity

// Fetch all the questions from the database
$result = $conn->query("SELECT * FROM questions");
$questions = [];
while ($question = $result->fetch_assoc()) {
    $questions[] = $question;
}

// Total number of questions
$total_questions = count($questions);

// Set the exam time limit in seconds (e.g., 30 minutes)
$exam_time_limit = 30 * 60; // 30 minutes
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Exam</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        let currentQuestion = 0;
        let timer;
        let timeRemaining = <?= $exam_time_limit ?>;

        function showQuestion(index) {
            currentQuestion = index;
            const questions = document.querySelectorAll('.question-card');
            questions.forEach((card, i) => {
                card.style.display = i === index ? 'block' : 'none';
            });
        }

        function nextQuestion() {
            if (currentQuestion < <?= $total_questions ?> - 1) {
                showQuestion(currentQuestion + 1);
            }
        }

        function previousQuestion() {
            if (currentQuestion > 0) {
                showQuestion(currentQuestion - 1);
            }
        }

        function jumpToQuestion(index) {
            showQuestion(index);
        }

        // Timer function
        function updateTimer() {
            const minutes = Math.floor(timeRemaining / 60);
            const seconds = timeRemaining % 60;
            document.getElementById("timer").innerText = `${minutes}:${seconds < 10 ? '0' + seconds : seconds}`;
            
            if (timeRemaining <= 0) {
                clearInterval(timer);
                alert("Time's up! Submitting your answers.");
                document.getElementById("examForm").submit(); // Automatically submit the form
            } else {
                timeRemaining--;
            }
        }

        // Start the timer when the page loads
        document.addEventListener('DOMContentLoaded', () => {
            showQuestion(currentQuestion);
            timer = setInterval(updateTimer, 1000); // Update the timer every second
        });
    </script>
</head>
<body>

<header>
    <h1>Online Exam</h1>
    <p>Time Remaining: <span id="timer"><?= floor($exam_time_limit / 60) ?>:00</span></p>
</header>

<div class="container">
    <form method="POST" action="submit_exam.php" id="examForm">
        <div class="question-nav">
            <button type="button" onclick="previousQuestion()">Previous</button>
            <button type="button" onclick="nextQuestion()">Next</button>
        </div>

        <div class="question-list">
            <?php foreach ($questions as $index => $question) { ?>
                <div class="question-card" id="question_<?= $index ?>" style="display:none;">
                    <h3>Question <?= $index + 1 ?>: <?= $question['question_text'] ?></h3>
                    <div class="options">
                        <label>
                            <input type="radio" name="answer_<?= $question['question_id'] ?>" value="A">
                            <?= $question['option_a'] ?>
                        </label><br>
                        <label>
                            <input type="radio" name="answer_<?= $question['question_id'] ?>" value="B">
                            <?= $question['option_b'] ?>
                        </label><br>
                        <label>
                            <input type="radio" name="answer_<?= $question['question_id'] ?>" value="C">
                            <?= $question['option_c'] ?>
                        </label><br>
                        <label>
                            <input type="radio" name="answer_<?= $question['question_id'] ?>" value="D">
                            <?= $question['option_d'] ?>
                        </label><br>
                    </div>
                </div>
            <?php } ?>
        </div>

        <div class="question-nav">
            <button type="submit">Submit Exam</button>
        </div>

        <div class="jump-to-question">
            <label for="jumpQuestion">Jump to Question:</label>
            <select id="jumpQuestion" onchange="jumpToQuestion(this.value)">
                <option value="">Select a question</option>
                <?php for ($i = 0; $i < $total_questions; $i++) { ?>
                    <option value="<?= $i ?>">Question <?= $i + 1 ?></option>
                <?php } ?>
            </select>
        </div>
    </form>
</div>

<footer>
    <p>&copy; 2024 Your Exam Platform</p>
</footer>

</body>
</html>
