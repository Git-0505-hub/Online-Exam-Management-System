<?php
// Ensure session is started only once (remove this if already called elsewhere)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Adjust the path to db.php based on the correct file structure
// Use absolute or relative path depending on the location of db.php
include_once  __DIR__ . '/../db.php';  // Absolute path might help if relative fails

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

// Handle question addition
if (isset($_POST['add_question'])) {
    $question_text = $_POST['question_text'];
    $option_a = $_POST['option_a'];
    $option_b = $_POST['option_b'];
    $option_c = $_POST['option_c'];
    $option_d = $_POST['option_d'];
    $correct_option = strtoupper($_POST['correct_option']);

    // Insert into questions table
    $stmt = $conn->prepare("INSERT INTO questions (question_text, option_a, option_b, option_c, option_d, correct_option) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $question_text, $option_a, $option_b, $option_c, $option_d, $correct_option);

    if ($stmt->execute()) {
        $success_message = "Question added successfully!";
    } else {
        $error_message = "Error adding question: " . $stmt->error;
    }
    $stmt->close();
}

// Handle question deletion
if (isset($_POST['delete_question'])) {
    $question_id = $_POST['question_id'];

    // Delete the question
    $stmt = $conn->prepare("DELETE FROM questions WHERE question_id = ?");
    $stmt->bind_param("i", $question_id);

    if ($stmt->execute()) {
        $success_message = "Question deleted successfully!";
    } else {
        $error_message = "Error deleting question: " . $stmt->error;
    }

    $stmt->close();
}

// Fetch all questions
$questions = $conn->query("SELECT * FROM questions");
?>

<section id="questions">
    <h2>Question Management</h2>

    <!-- Success/Error Messages -->
    <?php if (!empty($success_message)) echo "<p class='success-message'>$success_message</p>"; ?>
    <?php if (!empty($error_message)) echo "<p class='error-message'>$error_message</p>"; ?>

    <div class="form-container">
        <h3>Add New Question</h3>
        <form method="POST" action="">
            <input type="text" name="question_text" placeholder="Enter question text" required>
            <input type="text" name="option_a" placeholder="Option A" required>
            <input type="text" name="option_b" placeholder="Option B" required>
            <input type="text" name="option_c" placeholder="Option C" required>
            <input type="text" name="option_d" placeholder="Option D" required>
            <input type="text" name="correct_option" placeholder="Correct Option (A/B/C/D)" required>
            <button type="submit" name="add_question">Add Question</button>
        </form>
    </div>

    <h3>Existing Questions</h3>
    <table>
        <thead>
            <tr>
                <th>Sr.No.</th> <!-- Sr.No. Column -->
                <th>ID</th>
                <th>Question</th>
                <th>Options</th>
                <th>Correct Answer</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $serial_number = 1;  // Initialize Sr.No.
            while ($question = $questions->fetch_assoc()) { 
            ?>
                <tr>
                    <td><?php echo $serial_number++; ?></td> <!-- Display Sr.No. -->
                    <td><?php echo $question['question_id']; ?></td>
                    <td><?php echo $question['question_text']; ?></td>
                    <td>
                        A: <?php echo $question['option_a']; ?><br>
                        B: <?php echo $question['option_b']; ?><br>
                        C: <?php echo $question['option_c']; ?><br>
                        D: <?php echo $question['option_d']; ?>
                    </td>
                    <td><?php echo $question['correct_option']; ?></td>
                    <td>
                        <!-- Edit Link -->
                        <a href="admin/edit_question.php?question_id=<?php echo $question['question_id']; ?>">Edit</a>
                        
                        <!-- Delete Link -->
                        <a href="#" onclick="if (confirm('Are you sure you want to delete this question?')) { document.getElementById('delete-form-<?php echo $question['question_id']; ?>').submit(); }">Delete</a>
                        
                        <!-- Hidden Delete Form -->
                        <form id="delete-form-<?php echo $question['question_id']; ?>" method="POST" action="" style="display: none;">
                            <input type="hidden" name="question_id" value="<?php echo $question['question_id']; ?>" />
                            <input type="hidden" name="delete_question" value="1" />
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</section>

