<?php require_once '../auth.php'; 
requireAdmin();
?>
<?php
require_once '../auth.php';
requireAdmin();
require_once '../db.php';

$search = trim($_GET['search'] ?? '');

if ($search !== '') {
    $stmt = $pdo->prepare("SELECT * FROM students WHERE name LIKE ? OR email LIKE ? OR major LIKE ?");
    $stmt->execute(["%$search%", "%$search%", "%$search%"]);
} else {
    $stmt = $pdo->query("SELECT * FROM students");
}

$students = $stmt->fetchAll();

$year_labels = ['', '1st Year', '2nd Year', '3rd Year', '4th Year'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .search-bar {
            display: flex;
            gap: 0.75rem;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .search-bar input {
            flex: 1;
            padding: 0.65rem 1rem;
            border: 1px solid var(--border);
            border-radius: 6px;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.9rem;
            background: #fafaf9;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .search-bar input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(78,139,95,0.12);
            background: #fff;
        }

        .result-count {
            font-size: 0.82rem;
            color: var(--text-muted);
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
        <li><a href="list.php">Students</a></li>
        <li><a href="../courses/list.php">Courses</a></li>
        <?php endif; ?>
        <li><a href="../enrollment/list.php">Enrollments</a></li>
        <li><a href="../members.php">Team</a></li>
        <li><a href="../logout.php">Logout</a></li>
    </ul>
</nav>

<div class="container">

    <div class="page-header">
        <h1>All Students</h1>
        <p>Search and manage registered students.</p>
    </div>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
        <form method="GET" class="search-bar" style="flex: 1; margin-bottom: 0; margin-right: 1rem;">
            <input
                type="text"
                name="search"
                placeholder="Search by name, email or major..."
                value="<?= htmlspecialchars($search) ?>"
                autofocus
            >
            <button type="submit" class="btn btn-primary">Search</button>
            <?php if ($search): ?>
                <a href="list.php" class="btn btn-secondary">Clear</a>
            <?php endif; ?>
        </form>
        <a href="add.php" class="btn btn-primary">+ Add Student</a>
    </div>

    <div class="result-count">
        <?php if ($search): ?>
            <?= count($students) ?> result(s) for "<strong><?= htmlspecialchars($search) ?></strong>"
        <?php else: ?>
            <?= count($students) ?> student(s) total
        <?php endif; ?>
    </div>

    <?php if (count($students) > 0): ?>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Major</th>
                <th>Year</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($students as $student): ?>
            <tr>
                <td><?= $student['student_id'] ?></td>
                <td><?= htmlspecialchars($student['name']) ?></td>
                <td><?= htmlspecialchars($student['email']) ?></td>
                <td><?= htmlspecialchars($student['phone'] ?? '—') ?></td>
                <td><?= htmlspecialchars($student['major'] ?? '—') ?></td>
                <td>
                    <?php if (!empty($student['year'])): ?>
                        <span class="badge"><?= $year_labels[$student['year']] ?? '—' ?></span>
                    <?php else: ?>
                        —
                    <?php endif; ?>
                </td>
                <td>
                    <div class="actions">
                        <a href="edit.php?id=<?= $student['student_id'] ?>" class="btn btn-secondary btn-sm">Edit</a>
                        <a href="delete.php?id=<?= $student['student_id'] ?>" class="btn btn-danger btn-sm">Delete</a>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
    <div class="empty-state">
        <strong>No students found</strong>
        <p><?= $search ? 'Try a different name, email or major.' : 'No students have been added yet.' ?></p>
    </div>
    <?php endif; ?>

</div>

<footer>
    Student Course Enrollment System &mdash; <a href="../members.php">Meet the Team</a>
</footer>

</body>
</html>