<?php
$dbPath = __DIR__ . '/members.db';

if (file_exists($dbPath)) {
    unlink($dbPath);
}

$pdo = new PDO('sqlite:' . $dbPath);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$pdo->exec("
    CREATE TABLE IF NOT EXISTS members (
        id         INTEGER PRIMARY KEY AUTOINCREMENT,
        name       TEXT NOT NULL,
        student_id TEXT NOT NULL,
        email      TEXT NOT NULL,
        role       TEXT NOT NULL,
        bio        TEXT
    )
");

$members = [
    [
        'name'       => 'Jun',
        'student_id' => '413856039',
        'email'      => 'chinjunsi90@gmail.com',
        'role'       => 'Project Lead',
        'bio'        => 'Hello, I am Jun and I love math.',
    ],
    [
        'name'       => 'Jordan',
        'student_id' => '413856070',
        'email'      => ' xxjordanxx88@gmail.com',
        'role'       => 'Project Initiator and Backend Developer',
        'bio'        => 'Hello I am Jordan! I love to study!',
    ],
    [
        'name'       => 'Terence Tseng',
        'student_id' => '413856013',
        'email'      => 'terencet.301@gmail.com',
        'role'       => 'Frontend Developer',
        'bio'        => 'Hi I am Terence studying at TKU',
    ],
];

$stmt = $pdo->prepare("
    INSERT INTO members (name, student_id, email, role, bio)
    VALUES (:name, :student_id, :email, :role, :bio)
");

foreach ($members as $m) {
    $stmt->execute($m);
    echo "Inserted: {$m['name']}\n";
}

echo "Done!\n";
