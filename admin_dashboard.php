

<?php
session_start();
include 'db.php';

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// Handle student deletion
if (isset($_GET['delete_student'])) {
    $student_id = $_GET['delete_student'];
    $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $stmt->close();
    header("Location: admin_dashboard.php"); // Refresh the page
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <style>
              body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
        }
        .sidebar {
            background-color: #2c3e50;
            color: #ecf0f1;
            width: 250px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
        }
        .sidebar nav ul {
            list-style: none;
            padding: 0;
        }
        .sidebar nav ul li {
            margin: 15px 0;
        }
        .sidebar nav ul li a {
            color: #ecf0f1;
            text-decoration: none;
            font-size: 16px;
        }
        main {
            margin-left: 270px;
            padding: 20px;
            width: calc(100% - 270px);
        }
        h1 {
            color: #34495e;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #bdc3c7;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #34495e;
            color: #ecf0f1;
        }
        .form-container {
            background-color: #ecf0f1;
            padding: 20px;
            border: 1px solid #bdc3c7;
            margin-top: 20px;
        }
        .form-container h3 {
            margin-top: 0;
        }
        .form-container input, .form-container button {
            margin: 5px 0;
            padding: 10px;
            width: 100%;
        }
        .success-message {
            color: #27ae60;
        }
        .error-message {
            color: #e74c3c;
        }
        footer {
            position: fixed;
            
            margin-top: 10px;
            text-align: center;
            color: #7f8c8d;
        }
        .action-links a {
            margin-right: 10px;
            color: #3498db;
            text-decoration: none;
        }
        .action-links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <nav>
            <ul>
                <li><a href="admin_dashboard.php">Dashboard</a></li>
                <li><a href="results.php">View Results</a></li>
                <li><a href="?page=students">Manage Students</a></li>
                <li><a href="?page=questions">Manage Questions</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
    </aside>

    <!-- Main Content -->
    <main>
        <header>
            <h1>Admin Dashboard</h1>
            <p>Welcome, <?php echo $_SESSION['username']; ?>!</p>
        </header>

        <?php
        // Determine which page to include
        if (isset($_GET['page'])) {
            $page = $_GET['page'];
            if ($page == 'students') {
                include 'admin/manage_students.php';
            } elseif ($page == 'questions') {
                include 'admin/manage_questions.php';
            }
        } else {
            echo "<p>Please choose a management option from the sidebar.</p>";
        }
        ?>

    </main>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 Online Exam System. All rights reserved.</p>
    </footer>
</body>
</html>
