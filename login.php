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
    <title>Login – Student Enrollment System</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #1a3a2a 0%, #2d6e4e 100%);
        }

        .login-wrapper {
            width: 100%;
            max-width: 420px;
            padding: 1.5rem;
        }

        .login-brand {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-brand h1 {
            font-family: 'DM Serif Display', serif;
            color: #fff;
            font-size: 1.8rem;
            font-weight: 400;
            line-height: 1.2;
            margin-bottom: 0.4rem;
        }

        .login-brand p {
            color: rgba(255,255,255,0.6);
            font-size: 0.875rem;
        }

        .login-card {
            background: #fff;
            border-radius: 14px;
            padding: 2.25rem 2rem;
            box-shadow: 0 20px 60px rgba(0,0,0,0.25);
        }

        .login-card h2 {
            font-family: 'DM Serif Display', serif;
            font-size: 1.4rem;
            font-weight: 400;
            color: var(--primary);
            margin-bottom: 1.5rem;
        }

        .login-card .form-group {
            margin-bottom: 1.1rem;
        }

        .login-card .form-group label {
            display: block;
            font-size: 0.78rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            color: var(--text-muted);
            margin-bottom: 0.4rem;
        }

        .login-card .form-group input {
            width: 100%;
            padding: 0.7rem 0.9rem;
            border: 1.5px solid var(--border);
            border-radius: 8px;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.95rem;
            color: var(--text);
            background: #fafaf9;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .login-card .form-group input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(78,139,95,0.12);
            background: #fff;
        }

        .login-btn {
            width: 100%;
            padding: 0.75rem;
            background: var(--primary);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            margin-top: 0.5rem;
            transition: opacity 0.2s, transform 0.1s;
        }

        .login-btn:hover { opacity: 0.88; }
        .login-btn:active { transform: scale(0.98); }

        .login-footer {
            text-align: center;
            margin-top: 1.5rem;
            color: rgba(255,255,255,0.5);
            font-size: 0.78rem;
        }
    </style>
</head>
<body>

<div class="login-wrapper">

    <div class="login-brand">
        <h1>Student Enrollment System</h1>
        <p>Sign in to manage your courses</p>
    </div>

    <div class="login-card">
        <h2>Welcome back</h2>

        <?php if ($error): ?>
            <div class="alert alert-error" style="margin-bottom: 1.25rem;"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Student ID or Admin</label>
                <input type="text" name="username" placeholder="Enter your ID" required autofocus>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="login-btn">Sign In</button>
        </form>
    </div>

    <div class="login-footer">
        Student Course Enrollment System
    </div>

</div>

</body>
</html>