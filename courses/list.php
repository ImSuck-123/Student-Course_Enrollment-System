<?php
require_once '../db.php';

$stmt = $pdo->query("SELECT * FROM courses");
$courses = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Courses</title>
</head>
<body>
    <h1>All Courses</h1>
    <a href="add.php">Add New Course</a>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Course Name</th>
            <th>Credits</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($courses as $course): ?>
        <tr>
            <td><?= $course['course_id'] ?></td>
            <td><?= $course['course_name'] ?></td>
            <td><?= $course['credits'] ?></td>
            <td>
                <a href="edit.php?id=<?= $course['course_id'] ?>">Edit</a>
                <a href="delete.php?id=<?= $course['course_id'] ?>">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>