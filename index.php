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
    <h1>Student Course Enrollment System</h1>
    <p>Welcome, <strong><?= htmlspecialchars($_SESSION['name'] ?? 'Admin') ?></strong>!
    <a href="logout.php">Logout</a></p>

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

    <?php if (isAdmin()): ?>
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
            <li><a href="enrollment/list.php">View All Enrollments</a></li>
            <li><a href="enrollment/enroll.php">Enroll a Student</a></li>
        </ul>

    <?php else: ?>
        <h2>My Courses</h2>
        <ul>
            <li><a href="enrollment/list.php?student_id=<?= $_SESSION['student_id'] ?>">View My Courses</a></li>
            <li><a href="enrollment/enroll.php">Enroll in a Course</a></li>
        </ul>
    <?php endif; ?>

</body>
</html>
