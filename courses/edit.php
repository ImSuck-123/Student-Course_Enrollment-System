<?php
require_once '../db.php';

$error = '';
$success = '';

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
    $course_name = trim($_POST['course_name']);
    $credits     = trim($_POST['credits']);

    if (empty($course_name)) {
        $error = 'Course name is required.';
    } else {
        $stmt = $pdo->prepare("UPDATE courses SET course_name = ?, credits = ? WHERE course_id = ?");
        $stmt->execute([$course_name, $credits, $id]);
        $success = 'Course updated successfully!';

        $stmt = $pdo->prepare("SELECT * FROM courses WHERE course_id = ?");
        $stmt->execute([$id]);
        $course = $stmt->fetch();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Course</title>
</head>
<body>
    <h1>Edit Course</h1>
    <a href="list.php">Back to list</a>

    <?php if ($error): ?>
        <p style="color:red"><?= $error ?></p>
    <?php endif; ?>

    <?php if ($success): ?>
        <p style="color:green"><?= $success ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>Course Name:
            <input type="text" name="course_name" value="<?= htmlspecialchars($course['course_name']) ?>" required>
        </label><br><br>
        <label>Credits:
            <input type="number" name="credits" value="<?= $course['credits'] ?>" min="1" max="6">
        </label><br><br>
        <button type="submit">Save Changes</button>
    </form>
</body>
</html>