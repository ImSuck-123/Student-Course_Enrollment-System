<?php require_once '../auth.php'; 
requireAdmin();
?>
<?php
require_once '../db.php';

$stmt = $pdo->query("SELECT * FROM students");
$students = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Students</title>
</head>
<body>
    <h1>All Students</h1>
    <p><a href="../index.php">Back to Home</a></p>
    <a href="add.php">Add New Student</a>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($students as $student): ?>
        <tr>
            <td><?= $student['student_id'] ?></td>
            <td><?= $student['name'] ?></td>
            <td><?= $student['email'] ?></td>
            <td>
                <a href="edit.php?id=<?= $student['student_id'] ?>">Edit</a>
                <a href="delete.php?id=<?= $student['student_id'] ?>">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>