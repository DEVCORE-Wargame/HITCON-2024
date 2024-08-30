<?php
require_once __DIR__ . '/.includes.php';

login_required();

if (!isset($_POST['content'])) {
    die('Content is required');
}

$content = $_POST['content'];
$matches = [];
preg_match('/^# (.+)[\r\n]*$/m', $content, $matches);

$uuid = uuidv4();
$title = $matches[1] ?? 'Untitled';
$note_path = NOTE_PATH . '/' . bin2hex(random_bytes(16));
$owner = $_SESSION['username'];

$stmt = $db->prepare("INSERT INTO notes (id, owner, title, path) VALUES ('$uuid', '$owner', '$title', '$note_path')");
$stmt->execute();

file_put_contents($note_path, json_encode([
    'title' => $title,
    'owner' => $owner,
    'content' => $content,
    'is_public' => isset($_POST['is_public']),
]));

redirect('/');
