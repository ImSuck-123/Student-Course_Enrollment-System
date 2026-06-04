<?php
require_once 'db.php';
$members = $pdo->query("SELECT * FROM members")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Team</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .team-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 1.25rem;
        }

        .team-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 1.75rem 1.5rem;
            box-shadow: var(--shadow);
        }

        .team-card .member-name {
            font-family: 'DM Serif Display', serif;
            font-size: 1.25rem;
            color: var(--primary);
            font-weight: 400;
            margin-bottom: 0.25rem;
        }

        .team-card .member-role {
            display: inline-block;
            padding: 0.2rem 0.65rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            background: var(--accent-light);
            color: var(--primary);
            margin-bottom: 1rem;
        }

        .team-card .member-meta {
            font-size: 0.82rem;
            color: var(--text-muted);
            margin-bottom: 0.3rem;
        }

        .team-card .member-bio {
            font-size: 0.88rem;
            color: var(--text);
            margin-top: 0.75rem;
            padding-top: 0.75rem;
            border-top: 1px solid var(--border);
            line-height: 1.55;
        }
    </style>
</head>
<body>

<nav>
    <a href="index.php" class="brand">Student Enrollment System</a>
    <ul>
        <li><a href="students/list.php">Students</a></li>
        <li><a href="courses/list.php">Courses</a></li>
        <li><a href="enrollment/list.php">Enrollments</a></li>
        <li><a href="members.php">Team</a></li>
    </ul>
</nav>

<div class="container">

    <div class="page-header">
        <h1>Our Team</h1>
        <p>The people behind the Student Course Enrollment System.</p>
    </div>

    <div class="team-grid">
        <?php foreach ($members as $m): ?>
        <div class="team-card">
            <div class="member-name"><?= htmlspecialchars($m['name']) ?></div>
            <span class="member-role"><?= htmlspecialchars($m['role']) ?></span>
            <div class="member-meta">ID: <?= htmlspecialchars($m['student_id']) ?></div>
            <div class="member-meta"><?= htmlspecialchars($m['email']) ?></div>
            <?php if (!empty($m['bio'])): ?>
            <div class="member-bio"><?= htmlspecialchars($m['bio']) ?></div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>

</div>

<footer>
    Student Course Enrollment System &mdash; <a href="members.php">Meet the Team</a>
</footer>

</body>
</html>
