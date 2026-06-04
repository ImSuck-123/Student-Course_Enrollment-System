<?php require_once '../auth.php'; 
requireAdmin();
?>
<?php
require_once '../db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $course_name = trim($_POST['course_name']);
    $credits     = trim($_POST['credits']);

    if (empty($course_name)) {
        $error = 'Course name is required.';
    } else {
        $stmt = $pdo->prepare("INSERT INTO courses (course_name, credits) VALUES (?, ?)");
        $stmt->execute([$course_name, $credits]);
        $success = 'Course added successfully!';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Course</title>
</head>
<body>
    <h1>Add New Course</h1>
    <a href="list.php">Back to list</a>

    <?php if ($error): ?>
        <p style="color:red"><?= $error ?></p>
    <?php endif; ?>

    <?php if ($success): ?>
        <p style="color:green"><?= $success ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>Course Name:
            <input type="text" name="course_name" required>
        </label><br><br>
        <label>Credits:
            <input type="number" name="credits" value="3" min="1" max="6">
        </label><br><br>
        <button type="submit">Add Course</button>
    </form>
</body>
</html>