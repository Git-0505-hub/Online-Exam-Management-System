-- Database Creation Script for Online Examination System
-- Run this script in phpMyAdmin or directly in MySQL

-- Create 'users' table (students and admin)
CREATE TABLE IF NOT EXISTS users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('student', 'admin') DEFAULT 'student'
);

-- Create 'questions' table
CREATE TABLE IF NOT EXISTS questions (
    question_id INT PRIMARY KEY AUTO_INCREMENT,
    question_text TEXT NOT NULL,
    option_a VARCHAR(255),
    option_b VARCHAR(255),
    option_c VARCHAR(255),
    option_d VARCHAR(255),
    correct_option CHAR(1) CHECK (correct_option IN ('A', 'B', 'C', 'D'))
);

-- Create 'exams' table
CREATE TABLE IF NOT EXISTS exams (
    exam_id INT PRIMARY KEY AUTO_INCREMENT,
    exam_name VARCHAR(100) NOT NULL,
    start_time DATETIME,
    end_time DATETIME
);

-- Create 'results' table
CREATE TABLE IF NOT EXISTS results (
    result_id INT AUTO_INCREMENT PRIMARY KEY,
    exam_id INT,
    student_id INT,  -- student_id is referenced from the 'users' table
    time_taken INT,   -- Time in seconds
    score INT,        -- Score of the student in the exam
    FOREIGN KEY (exam_id) REFERENCES exams(exam_id),
    FOREIGN KEY (student_id) REFERENCES users(user_id)
);

-- Insert sample data into 'users' table (admin and student)
INSERT INTO users (username, password, role) VALUES
('admin', '$2y$10$8Gu6o/LXikE8p/Kyy3/GE.O.xKp6mMQ4aLnNZRMxTQf2CEPH7lD1G', 'admin'), -- Password: 'admin123'
('student1', '$2y$10$4I0G8ucSf.5T1bi3ZVpLXuqJhgF23VW5.LJ8uHho/xFI.WA8M35yK', 'student'); -- Password: 'student123'

-- Insert sample data into 'exams' table
INSERT INTO exams (exam_name, start_time, end_time) VALUES
('Math Quiz', '2024-11-15 10:00:00', '2024-11-15 11:00:00'),
('Science Test', '2024-11-16 09:00:00', '2024-11-16 10:00:00');

-- Insert sample data into 'questions' table for the 'Math Quiz' exam
INSERT INTO questions (question_text, option_a, option_b, option_c, option_d, correct_option) VALUES
('What is 2 + 2?', '3', '4', '5', '6', 'B'),
('What is the square root of 16?', '2', '4', '8', '16', 'B'),
('What is 10/2?', '2', '5', '10', '20', 'B');

-- Insert sample data into 'questions' table for the 'Science Test' exam
INSERT INTO questions (question_text, option_a, option_b, option_c, option_d, correct_option) VALUES
('What planet is known as the Red Planet?', 'Earth', 'Mars', 'Jupiter', 'Saturn', 'B'),
('What is the chemical symbol for water?', 'H2O', 'CO2', 'O2', 'N2', 'A'),
('Which gas is most abundant in Earth’s atmosphere?', 'Oxygen', 'Nitrogen', 'Carbon Dioxide', 'Hydrogen', 'B');

-- Insert sample data into 'results' table to record scores for students
-- Assuming student_id is 2 (student1) and they scored 85 in the Math Quiz and 92 in the Science Test
INSERT INTO results (exam_id, student_id, time_taken, score) VALUES
(1, 2, 1800, 85),  -- Math Quiz, 30 minutes (1800 seconds)
(2, 2, 2000, 92);  -- Science Test, 33 minutes (2000 seconds)
