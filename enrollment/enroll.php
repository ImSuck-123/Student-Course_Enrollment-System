<?php
require_once '../db.php';

$error = '';
$success = '';

// Load all students and courses for the dropdowns
$students = $pdo->query("SELECT * FROM students")->fetchAll();
$courses  = $pdo->query("SELECT * FROM courses")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = $_POST['student_id'];
    $course_id  = $_POST['course_id'];

    // Check if already enrolled
    $stmt = $pdo->prepare("SELECT * FROM enrollments WHERE student_id = ? AND course_id = ?");
    $stmt->execute([$student_id, $course_id]);
    $existing = $stmt->fetch();

    if ($existing) {
        $error = 'This student is already enrolled in that course.';
    } else {
        $stmt = $pdo->prepare("INSERT INTO enrollments (student_id, course_id) VALUES (?, ?)");
        $stmt->execute([$student_id, $course_id]);
        $success = 'Student enrolled successfully!';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Enroll Student</title>
</head>
<body>
    <h1>Enroll Student in Course</h1>
    <a href="list.php">View Enrollments</a>

    <?php if ($error): ?>
        <p style="color:red"><?= $error ?></p>
    <?php endif; ?>

    <?php if ($success): ?>
        <p style="color:green"><?= $success ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>Student:
            <select name="student_id" required>
                <option value="">-- Select Student --</option>
                <?php foreach ($students as $student): ?>
                <option value="<?= $student['student_id'] ?>">
                    <?= htmlspecialchars($student['name']) ?>
                </option>
                <?php endforeach; ?>
            </select>
        </label><br><br>

        <label>Course:
            <select name="course_id" required>
                <option value="">-- Select Course --</option>
                <?php foreach ($courses as $course): ?>
                <option value="<?= $course['course_id'] ?>">
                    <?= htmlspecialchars($course['course_name']) ?> (<?= $course['credits'] ?> credits)
                </option>
                <?php endforeach; ?>
            </select>
        </label><br><br>

        <button type="submit">Enroll</button>
    </form>
</body>
</html>