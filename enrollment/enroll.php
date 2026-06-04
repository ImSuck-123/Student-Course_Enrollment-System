<?php require_once '../auth.php';
if (isStudent()) {
    $_POST['student_id'] = $_SESSION['student_id'];
}?>

<?php
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
        $error = 'You are already enrolled in that course.';
    } else {
        $stmt = $pdo->prepare("INSERT INTO enrollments (student_id, course_id) VALUES (?, ?)");
        $stmt->execute([$student_id, $course_id]);
        $success = 'Enrolled successfully!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enroll in a Course</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .course-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .course-tile {
            position: relative;
        }

        .course-tile input[type="radio"] {
            display: none;
        }

        .course-tile label {
            display: block;
            background: var(--surface);
            border: 2px solid var(--border);
            border-radius: var(--radius);
            padding: 1.25rem;
            cursor: pointer;
            transition: border-color 0.15s, box-shadow 0.15s;
            height: 100%;
            text-transform: none;
            letter-spacing: 0;
            font-weight: 400;
            color: var(--text);
            font-size: 0.9rem;
        }

        .course-tile label:hover {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(78,139,95,0.1);
        }

        .course-tile input[type="radio"]:checked + label {
            border-color: var(--accent);
            background: var(--accent-light);
            box-shadow: 0 0 0 3px rgba(78,139,95,0.15);
        }

        .course-tile .course-name {
            font-weight: 600;
            color: var(--primary);
            font-size: 0.95rem;
            margin-bottom: 0.5rem;
            display: block;
        }

        .course-tile .credit-badge {
            display: inline-block;
            padding: 0.2rem 0.6rem;
            background: #e8f0eb;
            color: var(--accent);
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .course-tile input[type="radio"]:checked + label .credit-badge {
            background: rgba(78,139,95,0.2);
        }

        .checkmark {
            position: absolute;
            top: 0.75rem;
            right: 0.75rem;
            width: 20px;
            height: 20px;
            background: var(--accent);
            border-radius: 50%;
            display: none;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 0.7rem;
        }

        .course-tile input[type="radio"]:checked ~ .checkmark {
            display: flex;
        }

        .student-box {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            background: var(--accent-light);
            border: 1px solid #a8d5b5;
            border-radius: 8px;
            padding: 0.85rem 1rem;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            color: var(--primary);
            font-weight: 500;
        }

        .student-avatar {
            width: 36px;
            height: 36px;
            background: var(--primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 0.85rem;
            flex-shrink: 0;
        }

        .form-actions {
            display: flex;
            gap: 0.75rem;
            align-items: center;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border);
        }

        .student-select-group {
            margin-bottom: 1.5rem;
        }

        .student-select-group label {
            display: block;
            font-size: 0.78rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            color: var(--text-muted);
            margin-bottom: 0.4rem;
        }

        .student-select-group select {
            width: 100%;
            max-width: 360px;
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

        .student-select-group select:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(78,139,95,0.12);
        }

        .section-label {
            font-size: 0.78rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            color: var(--text-muted);
            margin-bottom: 0.85rem;
            display: block;
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
        <h1>Enroll in a Course</h1>
        <p>Select a course below to enroll.</p>
    </div>

    <?php if ($error): ?>
        <div class="alert alert-error"><?= $error ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>

    <form method="POST">

        <?php if (isStudent()): ?>
            <input type="hidden" name="student_id" value="<?= $_SESSION['student_id'] ?>">
            <div class="student-box">
                <div class="student-avatar"><?= strtoupper(substr($_SESSION['name'], 0, 1)) ?></div>
                <div>
                    <div style="font-weight:600;"><?= htmlspecialchars($_SESSION['name']) ?></div>
                    <div style="font-size:0.8rem; opacity:0.7;">Enrolling as this student</div>
                </div>
            </div>
        <?php else: ?>
            <div class="student-select-group">
                <label>Select Student</label>
                <select name="student_id" required>
                    <option value="">-- Select Student --</option>
                    <?php foreach ($students as $student): ?>
                    <option value="<?= $student['student_id'] ?>">
                        <?= htmlspecialchars($student['name']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
        <?php endif; ?>

        <span class="section-label">Choose a Course</span>

        <div class="course-grid">
            <?php foreach ($courses as $course): ?>
            <div class="course-tile">
                <input type="radio" name="course_id" id="course_<?= $course['course_id'] ?>" value="<?= $course['course_id'] ?>" required>
                <label for="course_<?= $course['course_id'] ?>">
                    <span class="course-name"><?= htmlspecialchars($course['course_name']) ?></span>
                    <span class="credit-badge"><?= $course['credits'] ?> credits</span>
                </label>
                <div class="checkmark">✓</div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Confirm Enrollment</button>
            <a href="list.php" class="btn btn-secondary">Cancel</a>
        </div>

    </form>

</div>

<footer>
    Student Course Enrollment System &mdash; <a href="../members.php">Meet the Team</a>
</footer>

</body>
</html>