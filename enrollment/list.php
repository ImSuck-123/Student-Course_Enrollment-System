<?php
require_once '../auth.php';
require_once '../db.php';

$students = $pdo->query("SELECT * FROM students")->fetchAll();

$enrollments      = [];
$selected_student = null;

if (isset($_GET['student_id']) && $_GET['student_id'] !== '') {
    $student_id = $_GET['student_id'];

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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enrollments</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .filter-bar {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 1.25rem 1.5rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            box-shadow: var(--shadow);
        }

        .filter-bar label {
            font-size: 0.82rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--text-muted);
            white-space: nowrap;
        }

        .filter-bar select {
            flex: 1;
            padding: 0.65rem 0.9rem;
            border: 1px solid var(--border);
            border-radius: 6px;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.9rem;
            color: var(--text);
            background: #fafaf9;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .filter-bar select:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(78,139,95,0.12);
            background: #fff;
        }

        .section-title {
            font-family: 'DM Serif Display', serif;
            font-size: 1.25rem;
            color: var(--primary);
            font-weight: 400;
            margin-bottom: 1rem;
        }

        .badge {
            display: inline-block;
            padding: 0.2rem 0.6rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            background: var(--accent-light);
            color: var(--primary);
        }

        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: var(--text-muted);
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
        }

        .empty-state p {
            font-size: 0.95rem;
            margin-top: 0.5rem;
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

    <div class="page-header" style="display: flex; justify-content: space-between; align-items: flex-start;">
        <div>
            <h1>Enrollments</h1>
            <p>View and manage course enrollments by student.</p>
        </div>
        <a href="enroll.php" class="btn btn-primary">+ Enroll Student</a>
    </div>

    <form method="GET">
        <div class="filter-bar">
            <label for="student_id">Filter by Student</label>
            <select name="student_id" id="student_id" onchange="this.form.submit()">
                <option value="">— Select a student —</option>
                <?php foreach ($students as $student): ?>
                <option value="<?= $student['student_id'] ?>"
                    <?= (isset($_GET['student_id']) && $_GET['student_id'] == $student['student_id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($student['name']) ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>
    </form>

    <?php if ($selected_student): ?>

        <p class="section-title">
            Courses for <?= htmlspecialchars($selected_student['name']) ?>
        </p>

        <?php if (count($enrollments) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Course Name</th>
                    <th>Credits</th>
                    <th>Enrolled At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($enrollments as $enrollment): ?>
                <tr>
                    <td><?= htmlspecialchars($enrollment['course_name']) ?></td>
                    <td><span class="badge"><?= $enrollment['credits'] ?> cr</span></td>
                    <td style="color: var(--text-muted); font-size: 0.85rem;">
                        <?= date('M j, Y', strtotime($enrollment['enrolled_at'])) ?>
                    </td>
                    <td>
                        <a href="delete.php?id=<?= $enrollment['enrollment_id'] ?>" class="btn btn-danger btn-sm">Drop</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
        <div class="empty-state">
            <strong>Not enrolled in any courses</strong>
            <p><a href="enroll.php" style="color: var(--accent);">Enroll this student</a> in a course to get started.</p>
        </div>
        <?php endif; ?>

    <?php elseif (!isset($_GET['student_id'])): ?>
        <div class="empty-state">
            <strong>Select a student above</strong>
            <p>Choose a student from the dropdown to view their enrollments.</p>
        </div>
    <?php endif; ?>

</div>

<footer>
    Student Course Enrollment System &mdash; <a href="../members.php">Meet the Team</a>
</footer>

</body>
</html>
