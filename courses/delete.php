
<?php
require_once '../auth.php';
requireAdmin();
require_once '../db.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    die("No course ID provided.");
}

$stmt = $pdo->prepare("SELECT * FROM courses WHERE course_id = ?");
$stmt->execute([$id]);
$course = $stmt->fetch();

if (!$course) {
    die("Course not found.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("DELETE FROM courses WHERE course_id = ?");
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
    <title>Delete Course</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .form-actions {
            display: flex;
            gap: 0.75rem;
            align-items: center;
            margin-top: 1.75rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border);
        }
    </style>
</head>
<body>

<nav>
    <a href="../index.php" class="brand">Student Enrollment System</a>
    <ul>
        <?php if (isAdmin()): ?>
        <li><a href="../students/list.php">Students</a></li>
        <li><a href="list.php">Courses</a></li>
        <?php endif; ?>
        <li><a href="../enrollment/list.php">Enrollments</a></li>
        <li><a href="../members.php">Team</a></li>
        <li><a href="../logout.php">Logout</a></li>
    </ul>
</nav>

<div class="container">

    <div class="page-header">
        <h1>Delete Course</h1>
        <p>This action cannot be undone.</p>
    </div>

    <div class="form-card">
        <p style="font-size: 0.95rem; margin-bottom: 1rem;">
            Are you sure you want to delete <strong><?= htmlspecialchars($course['course_name']) ?></strong>?
        </p>
        <div class="alert alert-error">This will also remove all student enrollments for this course.</div>
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
