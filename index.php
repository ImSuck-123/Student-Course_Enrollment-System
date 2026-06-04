<?php
require_once 'auth.php';
require_once 'db.php';

$student_count    = $pdo->query("SELECT COUNT(*) FROM students")->fetchColumn();
$course_count     = $pdo->query("SELECT COUNT(*) FROM courses")->fetchColumn();
$enrollment_count = $pdo->query("SELECT COUNT(*) FROM enrollments")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Enrollment System</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<nav>
    <a href="index.php" class="brand">Student Enrollment System</a>
    <ul>
        <?php if (isAdmin()): ?>
        <li><a href="students/list.php">Students</a></li>
        <li><a href="courses/list.php">Courses</a></li>
        <?php endif; ?>
        <li><a href="enrollment/list.php">Enrollments</a></li>
        <li><a href="members.php">Team</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</nav>

<div class="container">

    <div class="page-header">
        <h1>Dashboard</h1>
        <p>Welcome! Manage students, courses, and enrollments from here.</p>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <span class="stat-number"><?= $student_count ?></span>
            <span class="stat-label">Total Students</span>
        </div>
        <div class="stat-card">
            <span class="stat-number"><?= $course_count ?></span>
            <span class="stat-label">Total Courses</span>
        </div>
        <div class="stat-card">
            <span class="stat-number"><?= $enrollment_count ?></span>
            <span class="stat-label">Total Enrollments</span>
        </div>
    </div>

    <div class="section-grid">
        <div class="card">
            <h2>Students</h2>
            <ul>
                <li><a href="students/list.php">View All Students</a></li>
                <li><a href="students/add.php">Add New Student</a></li>
            </ul>
        </div>
        <div class="card">
            <h2>Courses</h2>
            <ul>
                <li><a href="courses/list.php">View All Courses</a></li>
                <li><a href="courses/add.php">Add New Course</a></li>
            </ul>
        </div>
        <div class="card">
            <h2>Enrollments</h2>
            <ul>
                <li><a href="enrollment/list.php">View Enrollments</a></li>
                <li><a href="enrollment/enroll.php">Enroll a Student</a></li>
            </ul>
        </div>
    </div>

</div>

<footer>
    Student Course Enrollment System &mdash; <a href="members.php">Meet the Team</a>
</footer>

</body>
</html>
