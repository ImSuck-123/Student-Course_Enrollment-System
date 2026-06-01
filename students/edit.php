<?php
require_once '../db.php';

$error = '';
$success = '';

// Get the student id from the URL e.g. edit.php?id=1
$id = $_GET['id'] ?? null;

if (!$id) {
    die("No student ID provided.");
}

// Load existing student data
$stmt = $pdo->prepare("SELECT * FROM students WHERE student_id = ?");
$stmt->execute([$id]);
$student = $stmt->fetch();

if (!$student) {
    die("Student not found.");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = trim($_POST['name']);
    $email = trim($_POST['email']);

    if (empty($name)) {
        $error = 'Name is required.';
    } else {
        $stmt = $pdo->prepare("UPDATE students SET name = ?, email = ? WHERE student_id = ?");
        $stmt->execute([$name, $email, $id]);
        $success = 'Student updated successfully!';

        // Reload updated data
        $stmt = $pdo->prepare("SELECT * FROM students WHERE student_id = ?");
        $stmt->execute([$id]);
        $student = $stmt->fetch();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Student</title>
</head>
<body>
    <h1>Edit Student</h1>
    <a href="list.php">Back to list</a>

    <?php if ($error): ?>
        <p style="color:red"><?= $error ?></p>
    <?php endif; ?>

    <?php if ($success): ?>
        <p style="color:green"><?= $success ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>Name: 
            <input type="text" name="name" value="<?= htmlspecialchars($student['name']) ?>" required>
        </label><br><br>
        <label>Email: 
            <input type="email" name="email" value="<?= htmlspecialchars($student['email']) ?>">
        </label><br><br>
        <button type="submit">Save Changes</button>
    </form>
</body>
</html>