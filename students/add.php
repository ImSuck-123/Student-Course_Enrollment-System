<?php require_once '../auth.php'; 
requireAdmin();
?>
<?php
require_once '../auth.php';
requireAdmin();
require_once '../db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name   = trim($_POST['name']);
    $email  = trim($_POST['email']);
    $phone  = trim($_POST['phone']);
    $year   = trim($_POST['year']);
    $major  = trim($_POST['major']);

    if (empty($name)) {
        $error = 'Name is required.';
    } else {
        $stmt = $pdo->prepare("INSERT INTO students (name, email, phone, year, major) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $email, $phone, $year, $major]);
        $success = 'Student added successfully!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .form-actions {
            display: flex;
            gap: 0.75rem;
            align-items: center;
            margin-top: 1.75rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border);
        }

        .year-options {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .year-option input[type="radio"] {
            display: none;
        }

        .year-option label {
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
            text-transform: none;
            letter-spacing: 0;
            white-space: nowrap;
        }

        .year-option input[type="radio"]:checked + label {
            border-color: var(--accent);
            background: var(--accent-light);
            color: var(--primary);
        }

        .year-option label:hover {
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
        <h1>Add New Student</h1>
        <p>Fill in the details below to register a new student.</p>
    </div>

    <?php if ($error): ?>
        <div class="alert alert-error"><?= $error ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>

    <div class="form-card">
        <form method="POST">

            <div class="form-row">
                <div class="form-group">
                    <label>Full Name <span style="color:var(--danger)">*</span></label>
                    <input type="text" name="name" placeholder="e.g. Alice Johnson" required>
                </div>
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" placeholder="e.g. alice@example.com">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="tel" name="phone" placeholder="e.g. +886 912 345 678">
                </div>
                <div class="form-group">
                    <label>Major</label>
                    <input type="text" name="major" placeholder="e.g. Computer Science">
                </div>
            </div>

            <div class="form-group">
                <label>Year</label>
                <div class="year-options">
                    <?php
                    $years = ['1st Year', '2nd Year', '3rd Year', '4th Year'];
                    foreach ($years as $i => $label):
                    ?>
                    <div class="year-option">
                        <input type="radio" name="year" id="year_<?= $i+1 ?>" value="<?= $i+1 ?>">
                        <label for="year_<?= $i+1 ?>"><?= $label ?></label>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Add Student</button>
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