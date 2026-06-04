<?php
require_once '../auth.php';
requireAdmin();
require_once '../db.php';

$error   = '';
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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Course</title>
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

        .credits-options {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .credits-option input[type="radio"] { display: none; }

        .credits-option label {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0.5rem 1rem;
            border: 2px solid var(--border);
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-muted);
            cursor: pointer;
            transition: all 0.15s;
            background: #fafaf9;
            white-space: nowrap;
        }

        .credits-option input[type="radio"]:checked + label {
            border-color: var(--accent);
            background: var(--accent-light);
            color: var(--primary);
        }

        .credits-option label:hover {
            border-color: var(--accent);
            color: var(--primary);
        }
    </style>
</head>
<body>

<nav>
    <a href="../index.php" class="brand">Student Enrollment System</a>
    <ul>
        <?php if (isAdmin()): ?>
        <li><a href="../students/list.php">Students</a></li>
        <li><a href="list.php">Courses</a></li>
        <?php endif; ?>
        <li><a href="../enrollment/list.php">Enrollments</a></li>
        <li><a href="../members.php">Team</a></li>
        <li><a href="../logout.php">Logout</a></li>
    </ul>
</nav>

<div class="container">

    <div class="page-header">
        <h1>Add New Course</h1>
        <p>Fill in the details below to create a new course.</p>
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
                <label>Course Name <span style="color:var(--danger)">*</span></label>
                <input type="text" name="course_name" placeholder="e.g. Introduction to Programming" required>
            </div>

            <div class="form-group">
                <label>Credits</label>
                <div class="credits-options">
                    <?php foreach ([1, 2, 3, 4, 5, 6] as $c): ?>
                    <div class="credits-option">
                        <input type="radio" name="credits" id="credits_<?= $c ?>" value="<?= $c ?>"
                            <?= ($c === 3) ? 'checked' : '' ?>>
                        <label for="credits_<?= $c ?>"><?= $c ?></label>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Add Course</button>
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
