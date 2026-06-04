<?php require_once '../auth.php';
if (isStudent()) {
    $_POST['student_id'] = $_SESSION['student_id'];
}?>

<?php
require_once '../db.php';

$error = '';
$success = '';

$students = $pdo->query("SELECT * FROM students")->fetchAll();
$courses  = $pdo->query("SELECT * FROM courses")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = isStudent() ? $_SESSION['student_id'] : $_POST['student_id']; // enforce again here too
    $course_id  = $_POST['course_id'];

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
    <?php if (isStudent()): ?>
    <!-- Hidden field, student can't tamper with the dropdown -->
    <input type="hidden" name="student_id" value="<?= $_SESSION['student_id'] ?>">
    <p><strong>Student:</strong> <?= htmlspecialchars($_SESSION['name']) ?></p>
<?php else: ?>
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
<?php endif; ?>

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