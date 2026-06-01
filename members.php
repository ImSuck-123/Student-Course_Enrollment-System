<?php
require_once 'db.php';
$members = $pdo->query("SELECT * FROM members")->fetchAll();
?>

<!DOCTYPE html>
<html>
<head><title>Our Team</title></head>
<body>
    <h1>Our Team</h1>
    <a href="index.php">Back to Home</a>
    <?php foreach ($members as $m): ?>
    <div>
        <h2><?= htmlspecialchars($m['name']) ?></h2>
        <p>Student ID: <?= htmlspecialchars($m['student_id']) ?></p>
        <p>Email: <?= htmlspecialchars($m['email']) ?></p>
        <p>Role: <?= htmlspecialchars($m['role']) ?></p>
        <p><?= htmlspecialchars($m['bio']) ?></p>
        <hr>
    </div>
    <?php endforeach; ?>
</body>
</html>