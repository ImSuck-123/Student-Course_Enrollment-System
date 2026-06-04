<?php
require_once '../auth.php';
requireAdmin();
require_once '../db.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    die("No student ID provided.");
}

$stmt = $pdo->prepare("SELECT * FROM students WHERE student_id = ?");
$stmt->execute([$id]);
$student = $stmt->fetch();

if (!$student) {
    die("Student not found.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("DELETE FROM students WHERE student_id = ?");
    $stmt->execute([$id]);
    header("Location: list.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Student</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<nav>
    <a href="../index.php" class="brand">Student Enrollment System</a>
    <ul>
        <?php if (isAdmin()): ?>
        <li><a href="list.php">Students</a></li>
        <li><a href="../courses/list.php">Courses</a></li>
        <?php endif; ?>
        <li><a href="../enrollment/list.php">Enrollments</a></li>
        <li><a href="../members.php">Team</a></li>
        <li><a href="../logout.php">Logout</a></li>
    </ul>
</nav>

<div class="container">

    <div class="page-header">
        <h1>Delete Student</h1>
        <p>This action cannot be undone.</p>
    </div>

    <div class="form-card">
        <p style="font-size: 0.95rem; margin-bottom: 1rem;">
            Are you sure you want to delete <strong><?= htmlspecialchars($student['name']) ?></strong>?
        </p>
        <div class="alert alert-error">This will also remove all their course enrollments.</div>
        <form method="POST">
            <div class="form-actions">
                <button type="submit" class="btn btn-danger">Yes, Delete</button>
                <a href="list.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>

</div>

<footer>
    Student Course Enrollment System &mdash; <a href="../members.php">Meet the Team</a>
</footer>

</body>
</html>
