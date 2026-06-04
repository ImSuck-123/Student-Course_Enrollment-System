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

    if ($username === 'admin' && $password === 'admin1234') {
        $_SESSION['user'] = 'admin';
        $_SESSION['role'] = 'admin';
        header("Location: index.php");
        exit;
    }

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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Student Enrollment System</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 2rem 1rem;
        }

        .login-brand {
            font-family: 'DM Serif Display', serif;
            font-size: 1.5rem;
            color: var(--primary);
            text-decoration: none;
            margin-bottom: 0.5rem;
        }

        .login-subtitle {
            font-size: 0.88rem;
            color: var(--text-muted);
            margin-bottom: 2rem;
        }

        .form-card {
            width: 100%;
            max-width: 400px;
        }

        .form-actions {
            margin-top: 1.5rem;
        }

        .form-actions .btn {
            width: 100%;
            text-align: center;
            padding: 0.7rem;
            font-size: 0.95rem;
        }

        .login-hint {
            text-align: center;
            font-size: 0.78rem;
            color: var(--text-muted);
            margin-top: 1.25rem;
        }
    </style>
</head>
<body>

    <span class="login-brand">Student Enrollment System</span>
    <p class="login-subtitle">Sign in to continue</p>

    <?php if ($error): ?>
        <div class="alert alert-error" style="width:100%;max-width:400px;margin-bottom:1rem;">
            <?= $error ?>
        </div>
    <?php endif; ?>

    <div class="form-card">
        <form method="POST">

            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" placeholder="Student ID or admin" autofocus required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Enter your password" required>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Sign In</button>
            </div>

        </form>
    </div>

    <p class="login-hint">Admin: username <strong>admin</strong> / password <strong>admin1234</strong></p>

</body>
</html>
