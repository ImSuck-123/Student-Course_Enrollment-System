<?php require_once '../auth.php'; 
requireAdmin();
?>
<?php
require_once '../db.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    die("No student ID provided.");
}

// Load student so we can show their name in the confirmation
$stmt = $pdo->prepare("SELECT * FROM students WHERE student_id = ?");
$stmt->execute([$id]);
$student = $stmt->fetch();

if (!$student) {
    die("Student not found.");
}

// Handle confirmation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("DELETE FROM students WHERE student_id = ?");
    $stmt->execute([$id]);
    header("Location: list.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Delete Student</title>
</head>
<body>
    <h1>Delete Student</h1>
    <a href="list.php">Back to list</a>

    <p>Are you sure you want to delete <strong><?= htmlspecialchars($student['name']) ?></strong>?</p>
    <p style="color:red">This will also delete all their enrollments.</p>

    <form method="POST">
        <button type="submit" style="color:red">Yes, Delete</button>
        <a href="list.php">Cancel</a>
    </form>
</body>
</html>