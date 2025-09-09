<?php
$admin_password = password_hash("admin123", PASSWORD_BCRYPT);
$student_password = password_hash("student123", PASSWORD_BCRYPT);

echo "Admin Hashed Password: " . $admin_password . "<br>";
echo "Student Hashed Password: " . $student_password;
?>
