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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Course</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .edit-hero {
            background: linear-gradient(135deg, var(--primary) 0%, #2d6e4e 100%);
            color: #fff;
            padding: 2.5rem 2rem 3.5rem;
            margin-bottom: -2rem;
        }

        .edit-hero h1 {
            font-family: 'DM Serif Display', serif;
            font-size: 2rem;
            font-weight: 400;
            margin-bottom: 0.25rem;
        }

        .edit-hero p {
            opacity: 0.75;
            font-size: 0.9rem;
        }

        .edit-hero a {
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            font-size: 0.85rem;
            display: inline-block;
            margin-bottom: 1rem;
            transition: color 0.2s;
        }

        .edit-hero a:hover {
            color: #fff;
        }

        .form-card {
            position: relative;
            z-index: 1;
        }

        .credit-options {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .credit-option input[type="radio"] {
            display: none;
        }

        .credit-option label {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 52px;
            height: 52px;
            border: 2px solid var(--border);
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-muted);
            cursor: pointer;
            transition: all 0.15s;
            background: #fafaf9;
            text-transform: none;
            letter-spacing: 0;
        }

        .credit-option input[type="radio"]:checked + label {
            border-color: var(--accent);
            background: var(--accent-light);
            color: var(--primary);
        }

        .credit-option label:hover {
            border-color: var(--accent);
            color: var(--primary);
        }

        .form-actions {
            display: flex;
            gap: 0.75rem;
            align-items: center;
            margin-top: 1.75rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border);
        }
    </style>
</head>
<body>

<nav>
    <a href="../index.php" class="brand">Student Enrollment System</a>
    <ul>
        <li><a href="../students/list.php">Students</a></li>
        <li><a href="list.php">Courses</a></li>
        <li><a href="../enrollment/list.php">Enrollments</a></li>
        <li><a href="../members.php">Team</a></li>
    </ul>
</nav>

<div class="edit-hero">
    <a href="list.php">← Back to Courses</a>
    <h1>Edit Course</h1>
    <p>Update the course details below</p>
</div>

<div class="container">

    <?php if ($error): ?>
        <div class="alert alert-error"><?= $error ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>

    <div class="form-card">
        <form method="POST">

            <div class="form-group">
                <label>Course Name</label>
                <input
                    type="text"
                    name="course_name"
                    value="<?= htmlspecialchars($course['course_name']) ?>"
                    placeholder="e.g. Introduction to Programming"
                    required
                >
            </div>

            <div class="form-group">
                <label>Credits</label>
                <div class="credit-options">
                    <?php for ($i = 1; $i <= 6; $i++): ?>
                    <div class="credit-option">
                        <input
                            type="radio"
                            name="credits"
                            id="credit_<?= $i ?>"
                            value="<?= $i ?>"
                            <?= $course['credits'] == $i ? 'checked' : '' ?>
                        >
                        <label for="credit_<?= $i ?>"><?= $i ?></label>
                    </div>
                    <?php endfor; ?>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Save Changes</button>
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