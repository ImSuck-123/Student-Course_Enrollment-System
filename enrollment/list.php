<?php require_once '../auth.php'; ?>
<?php
require_once '../db.php';

$students = $pdo->query("SELECT * FROM students")->fetchAll();

$enrollments = [];
$selected_student = null;

// If student, always force their own ID — ignore GET param
if (isStudent()) {
    $student_id = $_SESSION['student_id'];
} elseif (isset($_GET['student_id']) && $_GET['student_id'] !== '') {
    $student_id = $_GET['student_id'];
} else {
    $student_id = null;
}

if ($student_id) {
    $stmt = $pdo->prepare("SELECT * FROM students WHERE student_id = ?");
    $stmt->execute([$student_id]);
    $selected_student = $stmt->fetch();

    $stmt = $pdo->prepare("
        SELECT e.enrollment_id, c.course_name, c.credits, e.enrolled_at
        FROM enrollments e
        JOIN courses c ON e.course_id = c.course_id
        WHERE e.student_id = ?
        ORDER BY e.enrolled_at DESC
    ");
    $stmt->execute([$student_id]);
    $enrollments = $stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Enrollments</title>
</head>
<body>
    <h1>Student Enrollments</h1>
    <p><a href="../index.php">Back to Home</a></p>
    <a href="enroll.php">Enroll a Student</a>

    <?php if (!isStudent()): ?>
    <form method="GET">
        <label>Select Student:
            <select name="student_id" onchange="this.form.submit()">
                <option value="">-- Select Student --</option>
                <?php foreach ($students as $student): ?>
                <option value="<?= $student['student_id'] ?>"
                    <?= (isset($_GET['student_id']) && $_GET['student_id'] == $student['student_id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($student['name']) ?>
                </option>
                <?php endforeach; ?>
            </select>
        </label>
    </form>
    <?php endif; ?>

    <?php if ($selected_student): ?>
        <h2>Courses for <?= htmlspecialchars($selected_student['name']) ?></h2>

        <?php if (empty($enrollments)): ?>
            <p>This student is not enrolled in any courses yet.</p>
        <?php else: ?>
            <table border="1">
                <tr>
                    <th>Course Name</th>
                    <th>Credits</th>
                    <th>Enrolled At</th>
                    <th>Action</th>
                </tr>
                <?php foreach ($enrollments as $enrollment): ?>
                <tr>
                    <td><?= htmlspecialchars($enrollment['course_name']) ?></td>
                    <td><?= $enrollment['credits'] ?></td>
                    <td><?= $enrollment['enrolled_at'] ?></td>
                    <td>
                        <a href="delete.php?id=<?= $enrollment['enrollment_id'] ?>">Drop</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    <?php endif; ?>
</body>
</html>