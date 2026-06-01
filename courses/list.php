<?php
require_once '../db.php';

$stmt = $pdo->query("SELECT * FROM courses");
$courses = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Courses</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<nav>
    <a href="../index.php" class="brand">Student Enrollment System</a>
    <ul>
        <li><a href="../students/list.php">Students</a></li>
        <li><a href="list.php">Courses</a></li>
        <li><a href="../enrollment/list.php">Enrollments</a></li>
        <li><a href="../members.php">Team</a></li>
    </ul>
</nav>

<div class="container">

    <div class="page-header">
        <h1>All Courses</h1>
        <p>Browse and manage all available courses.</p>
    </div>

    <div style="margin-bottom: 1rem;">
        <a href="add.php" class="btn btn-primary">+ Add New Course</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Course Name</th>
                <th>Credits</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($courses as $course): ?>
            <tr>
                <td><?= $course['course_id'] ?></td>
                <td><?= $course['course_name'] ?></td>
                <td><?= $course['credits'] ?></td>
                <td>
                    <div class="actions">
                        <a href="edit.php?id=<?= $course['course_id'] ?>" class="btn btn-secondary btn-sm">Edit</a>
                        <a href="delete.php?id=<?= $course['course_id'] ?>" class="btn btn-danger btn-sm">Delete</a>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</div>

<footer>
    Student Course Enrollment System &mdash; <a href="../members.php">Meet the Team</a>
</footer>

</body>
</html>