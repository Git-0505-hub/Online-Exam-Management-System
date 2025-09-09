<?php
// Fetch all students
$students = $conn->query("SELECT * FROM users WHERE role = 'student'");
?>

<section id="students">
    <h2>Student Management</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Score</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($student = $students->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $student['user_id']; ?></td>
                    <td><?php echo $student['username']; ?></td>
                    <td><a href="admin/view_score.php?user_id=<?php echo $student['user_id']; ?>">View Score</a></td>
                    <td>
                        <a href="?delete_student=<?php echo $student['user_id']; ?>" onclick="return confirm('Are you sure you want to delete this student?')">Delete</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</section>
