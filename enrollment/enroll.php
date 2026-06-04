<?php
require_once '../auth.php';
require_once '../db.php';

$error   = '';
$success = '';

$students = $pdo->query("SELECT * FROM students")->fetchAll();
$courses  = $pdo->query("SELECT * FROM courses")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = isStudent() ? $_SESSION['student_id'] : $_POST['student_id'];
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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enroll Student</title>
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

        .student-display {
            padding: 0.65rem 0.9rem;
            background: var(--accent-light);
            border: 1px solid #a8d5b5;
            border-radius: 6px;
            font-size: 0.9rem;
            color: var(--primary);
            font-weight: 500;
        }
    </style>
</head>
<body>

<nav>
    <a href="../index.php" class="brand">Student Enrollment System</a>
    <ul>
        <?php if (isAdmin()): ?>
        <li><a href="../students/list.php">Students</a></li>
        <li><a href="../courses/list.php">Courses</a></li>
        <?php endif; ?>
        <li><a href="list.php">Enrollments</a></li>
        <li><a href="../members.php">Team</a></li>
        <li><a href="../logout.php">Logout</a></li>
    </ul>
</nav>

<div class="container">

    <div class="page-header">
        <h1>Enroll Student</h1>
        <p>Assign a student to a course.</p>
    </div>

    <?php if ($error): ?>
        <div class="alert alert-error"><?= $error ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>

    <div class="form-card">
        <form method="POST">

            <div class="form-group">
                <label>Student <span style="color:var(--danger)">*</span></label>
                <?php if (isStudent()): ?>
                    <input type="hidden" name="student_id" value="<?= $_SESSION['student_id'] ?>">
                    <div class="student-display"><?= htmlspecialchars($_SESSION['name']) ?></div>
                <?php else: ?>
                    <select name="student_id" required>
                        <option value="">— Select a student —</option>
                        <?php foreach ($students as $student): ?>
                        <option value="<?= $student['student_id'] ?>"
                            <?= (isset($_POST['student_id']) && $_POST['student_id'] == $student['student_id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($student['name']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label>Course <span style="color:var(--danger)">*</span></label>
                <select name="course_id" required>
                    <option value="">— Select a course —</option>
                    <?php foreach ($courses as $course): ?>
                    <option value="<?= $course['course_id'] ?>"
                        <?= (isset($_POST['course_id']) && $_POST['course_id'] == $course['course_id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($course['course_name']) ?> (<?= $course['credits'] ?> credits)
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Enroll</button>
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
