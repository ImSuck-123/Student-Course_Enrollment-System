<?php require_once '../auth.php'; ?>
<?php
require_once '../db.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    die("No enrollment ID provided.");
}

// Load enrollment with student and course info
$stmt = $pdo->prepare("
    SELECT e.enrollment_id, s.name AS student_name, c.course_name
    FROM enrollments e
    JOIN students s ON e.student_id = s.student_id
    JOIN courses c  ON e.course_id  = c.course_id
    WHERE e.enrollment_id = ?
");
$stmt->execute([$id]);
$enrollment = $stmt->fetch();

if (!$enrollment) {
    die("Enrollment not found.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("DELETE FROM enrollments WHERE enrollment_id = ?");
    $stmt->execute([$id]);
    header("Location: list.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Drop Course</title>
</head>
<body>
    <h1>Drop Course</h1>
    <a href="list.php">Back to list</a>

    <p>Are you sure you want to drop
        <strong><?= htmlspecialchars($enrollment['student_name']) ?></strong>
        from
        <strong><?= htmlspecialchars($enrollment['course_name']) ?></strong>?
    </p>

    <form method="POST">
        <button type="submit" style="color:red">Yes, Drop Course</button>
        <a href="list.php">Cancel</a>
    </form>
</body>
</html>