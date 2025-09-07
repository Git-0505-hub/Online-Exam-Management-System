<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

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
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 50px auto;
            background-color: white;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }

        .back-btn {
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            text-align: center;
            margin-top: 20px;
        }

        .back-btn:hover {
            background-color: #0056b3;
        }

        .print-btn {
            display: inline-block;
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            text-align: center;
            margin-bottom: 20px;
        }

        .print-btn:hover {
            background-color: #218838;
        }
    </style>
    <script>
        function printTable() {
            var printContent = document.getElementById('resultsTable').outerHTML;
            var newWindow = window.open('', '', 'height=600,width=800');
            newWindow.document.write('<html><head><title>Print Results</title></head><body>');
            newWindow.document.write(printContent);
            newWindow.document.write('</body></html>');
            newWindow.document.close();
            newWindow.print();
        }
    </script>
</head>
<body>

<div class="container">
    <h2>Exam Results</h2>

    <!-- Print Button -->
    <a href="javascript:void(0);" class="print-btn" onclick="printTable()">Print Results</a>

    <?php if ($_SESSION['role'] == 'admin'): ?>
        <table id="resultsTable">
            <thead>
                <tr>
                    <th>Sr. No.</th>
                    <th>User Name</th>
                    <th>Exam</th>
                    <th>Score</th>
                    <th>Time Taken</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Query to fetch results with user names
                $result = $conn->query("SELECT users.username, exams.exam_name, results.score, 
                                               TIMEDIFF(results.end_time, results.start_time) AS time_taken
                                        FROM results 
                                        JOIN users ON results.user_id = users.user_id
                                        JOIN exams ON results.exam_id = exams.exam_id");
                $sr_no = 1;
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$sr_no}</td>
                            <td>{$row['username']}</td>
                            <td>{$row['exam_name']}</td>
                            <td>{$row['score']}</td>
                            <td>{$row['time_taken']}</td>
                          </tr>";
                    $sr_no++;
                }
                ?>
            </tbody>
        </table>
    <?php else: ?>
        <table id="resultsTable">
            <thead>
                <tr>
                    <th>Sr. No.</th>
                    <th>Exam</th>
                    <th>Score</th>
                    <th>Time Taken</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // For user: No need for user name column, fetch their results
                $user_id = $_SESSION['user_id'];
                $result = $conn->query("SELECT exams.exam_name, results.score, 
                                               TIMEDIFF(results.end_time, results.start_time) AS time_taken
                                        FROM results 
                                        JOIN exams ON results.exam_id = exams.exam_id
                                        WHERE results.user_id = $user_id");
                $sr_no = 1;
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$sr_no}</td>
                            <td>{$row['exam_name']}</td>
                            <td>{$row['score']}</td>
                            <td>{$row['time_taken']}</td>
                          </tr>";
                    $sr_no++;
                }
                ?>
            </tbody>
        </table>
    <?php endif; ?>

    <a href="user_dashboard.php" class="back-btn">Back to Dashboard</a>
</div>

</body>
</html>
