<?php
require_once 'db.php';

// Get counts for the dashboard
$student_count    = $pdo->query("SELECT COUNT(*) FROM students")->fetchColumn();
$course_count     = $pdo->query("SELECT COUNT(*) FROM courses")->fetchColumn();
$enrollment_count = $pdo->query("SELECT COUNT(*) FROM enrollments")->fetchColumn();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Enrollment System</title>
</head>
<body>
    <h1>Student Course Enrollment System</h1>
    <p>Welcome! Use the links below to manage students, courses, and enrollments.</p>

    <hr>

    <h2>Dashboard</h2>
    <ul>
        <li>Total Students: <strong><?= $student_count ?></strong></li>
        <li>Total Courses: <strong><?= $course_count ?></strong></li>
        <li>Total Enrollments: <strong><?= $enrollment_count ?></strong></li>
    </ul>

    <hr>

    <h2>Students</h2>
    <ul>
        <li><a href="students/list.php">View All Students</a></li>
        <li><a href="students/add.php">Add New Student</a></li>
    </ul>

    <h2>Courses</h2>
    <ul>
        <li><a href="courses/list.php">View All Courses</a></li>
        <li><a href="courses/add.php">Add New Course</a></li>
    </ul>

    <h2>Enrollments</h2>
    <ul>
        <li><a href="enrollment/list.php">View Enrollments</a></li>
        <li><a href="enrollment/enroll.php">Enroll a Student</a></li>
    </ul>

    <hr>
    <a href="members.php">About Our Team</a>
</body>
</html>