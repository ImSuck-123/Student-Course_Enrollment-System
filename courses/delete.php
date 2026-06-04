<?php require_once '../auth.php'; 
requireAdmin();
?>
<?php
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
<html>
<head>
    <title>Delete Course</title>
</head>
<body>
    <h1>Delete Course</h1>
    <a href="list.php">Back to list</a>

    <p>Are you sure you want to delete <strong><?= htmlspecialchars($course['course_name']) ?></strong>?</p>
    <p style="color:red">This will also remove all student enrollments for this course.</p>

    <form method="POST">
        <button type="submit" style="color:red">Yes, Delete</button>
        <a href="list.php">Cancel</a>
    </form>
</body>
</html>