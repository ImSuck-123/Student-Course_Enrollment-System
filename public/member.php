<?php
// Handle both browser (?id=1) and CLI (id=1)
$id = $_GET['id'] ?? null;
if ($id === null && isset($argv)) {
    foreach ($argv as $arg) {
        if (str_starts_with($arg, 'id=')) {
            $id = substr($arg, 3);
        }
    }
}

$dbPath = __DIR__ . '/../private/members.db';

if (!file_exists($dbPath)) {
    die('Database not found. Run php private/setup.php first.');
}

$pdo = new PDO('sqlite:' . $dbPath);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($id === null) {
    $members = $pdo->query("SELECT id, name, role FROM members ORDER BY id")
                   ->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Our Team</title>
</head>
<body>
    <h1>Our Team</h1>
    <?php foreach ($members as $m): ?>
        <p>
            <a href="member.php?id=<?= (int)$m['id'] ?>">
                <?= htmlspecialchars($m['name']) ?>
            </a>
            - <?= htmlspecialchars($m['role']) ?>
        </p>
    <?php endforeach; ?>
</body>
</html>

<?php
} else {
    $id = filter_var($id, FILTER_VALIDATE_INT);
    if (!$id) {
        die('Invalid ID.');
    }

    $stmt = $pdo->prepare("SELECT * FROM members WHERE id = :id LIMIT 1");
    $stmt->execute([':id' => $id]);
    $member = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$member) {
        die('Member not found.');
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars($member['name']) ?></title>
</head>
<body>
    <a href="member.php">Back to all members</a>
    <h1><?= htmlspecialchars($member['name']) ?></h1>
    <p><strong>Student ID:</strong> <?= htmlspecialchars($member['student_id']) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($member['email']) ?></p>
    <p><strong>Role:</strong> <?= htmlspecialchars($member['role']) ?></p>
    <p><strong>Bio:</strong> <?= htmlspecialchars($member['bio']) ?></p>
</body>
</html>
<?php } ?>
