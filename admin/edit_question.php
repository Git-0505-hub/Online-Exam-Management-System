<?php
session_start();
include '../db.php'; // Adjusted path for the DB file

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

// Get question ID from URL
if (isset($_GET['question_id'])) {
    $question_id = $_GET['question_id'];

    // Fetch the question details
    $stmt = $conn->prepare("SELECT * FROM questions WHERE question_id = ?");
    $stmt->bind_param("i", $question_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $question = $result->fetch_assoc();
    $stmt->close();
} else {
    echo "Invalid question ID.";
    exit();
}

// Handle question update
if (isset($_POST['update_question'])) {
    $question_text = $_POST['question_text'];
    $option_a = $_POST['option_a'];
    $option_b = $_POST['option_b'];
    $option_c = $_POST['option_c'];
    $option_d = $_POST['option_d'];
    $correct_option = strtoupper($_POST['correct_option']);

    $stmt = $conn->prepare("UPDATE questions SET question_text = ?, option_a = ?, option_b = ?, option_c = ?, option_d = ?, correct_option = ? WHERE question_id = ?");
    $stmt->bind_param("ssssssi", $question_text, $option_a, $option_b, $option_c, $option_d, $correct_option, $question_id);

    if ($stmt->execute()) {
        echo "<p class='success-message'>Question updated successfully!</p>";
    } else {
        echo "<p class='error-message'>Error updating question: " . $stmt->error . "</p>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Question</title>
    <link rel="stylesheet" href="../styles.css">
    <style>
        /* Global styles */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            min-height: 100vh;
            background-color: #f4f4f9;
        }

        /* Sidebar styling */
        aside.sidebar {
            background-color: #2c3e50;
            width: 250px;
            height: 100vh;
            padding-top: 20px;
            position: fixed;
        }

        aside.sidebar nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        aside.sidebar nav ul li {
            text-align: center;
            margin: 15px 0;
        }

        aside.sidebar nav ul li a {
            color: white;
            font-size: 18px;
            text-decoration: none;
            display: block;
            padding: 10px 0;
            transition: background-color 0.3s;
        }

        aside.sidebar nav ul li a:hover {
            background-color: #0056b3;
        }

        /* Main content */
        main {
            margin-left: 270px;
            padding: 40px;
            width: calc(100% - 270px);
            background-color: #fff;
        }

        header {
            text-align: center;
            margin-bottom: 30px;
        }

        header h1 {
            font-size: 2rem;
            color: #007bff;
        }

        section {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        form input {
            width: 100%;
            padding: 12px;
            margin: 8px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 1rem;
        }

        form button {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1.1rem;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        form button:hover {
            background-color: #0056b3;
        }

        .success-message {
            color: green;
            text-align: center;
            margin: 10px 0;
        }

        .error-message {
            color: red;
            text-align: center;
            margin: 10px 0;
        }

        footer {
            text-align: center;
            margin-top: 40px;
            padding: 10px;
            background-color: #007bff;
            color: white;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <aside class="sidebar">
        <nav>
            <ul>
                <li><a href="../admin_dashboard.php">Dashboard</a></li>
                
                <li><a href="../logout.php">Logout</a></li>
            </ul>
        </nav>
    </aside>

    <!-- Main Content -->
    <main>
        <header>
            <h1>Edit Question</h1>
        </header>

        <section>
            <form method="POST" action="">
                <label for="question_text">Question Text:</label>
                <input type="text" name="question_text" id="question_text" value="<?php echo htmlspecialchars($question['question_text'], ENT_QUOTES, 'UTF-8'); ?>" required>

                <label for="option_a">Option A:</label>
                <input type="text" name="option_a" id="option_a" value="<?php echo htmlspecialchars($question['option_a'], ENT_QUOTES, 'UTF-8'); ?>" required>

                <label for="option_b">Option B:</label>
                <input type="text" name="option_b" id="option_b" value="<?php echo htmlspecialchars($question['option_b'], ENT_QUOTES, 'UTF-8'); ?>" required>

                <label for="option_c">Option C:</label>
                <input type="text" name="option_c" id="option_c" value="<?php echo htmlspecialchars($question['option_c'], ENT_QUOTES, 'UTF-8'); ?>" required>

                <label for="option_d">Option D:</label>
                <input type="text" name="option_d" id="option_d" value="<?php echo htmlspecialchars($question['option_d'], ENT_QUOTES, 'UTF-8'); ?>" required>

                <label for="correct_option">Correct Option:</label>
                <input type="text" name="correct_option" id="correct_option" value="<?php echo htmlspecialchars($question['correct_option'], ENT_QUOTES, 'UTF-8'); ?>" required>

                <button type="submit" name="update_question">Update Question</button>
            </form>
        </section>
    </main>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 Online Exam System. All rights reserved.</p>
    </footer>

</body>
</html>
