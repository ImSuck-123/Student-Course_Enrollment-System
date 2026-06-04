<?php
session_start();

if (isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Hardcoded admin check
    if ($username === 'admin' && $password === 'admin1234') {
        $_SESSION['user'] = 'admin';
        $_SESSION['role'] = 'admin';
        header("Location: index.php");
        exit;
    }

    // Student login — username is student_id
    require_once 'db.php';
    $stmt = $pdo->prepare("SELECT * FROM students WHERE student_id = ?");
    $stmt->execute([$username]);
    $student = $stmt->fetch();

    if ($student && password_verify($password, $student['password'])) {
        $_SESSION['user']       = $student['student_id'];
        $_SESSION['role']       = 'student';
        $_SESSION['student_id'] = $student['student_id'];
        $_SESSION['name']       = $student['name'];
        header("Location: index.php");
        exit;
    }

    $error = 'Invalid username or password.';
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <h1>Student Enrollment System</h1>
    <h2>Login</h2>

    <?php if ($error): ?>
        <p style="color:red"><?= $error ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>Username (Student ID or admin):
            <input type="text" name="username" required>
        </label><br><br>
        <label>Password:
            <input type="password" name="password" required>
        </label><br><br>
        <button type="submit">Login</button>
    </form>
</body>
</html>
