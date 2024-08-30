<?php
require_once __DIR__ . '/.includes.php';

if (!isset($_GET['id']) || !is_uuidv4($_GET['id'])) {
    die('Note not found');
}

$stmt = $db->prepare("SELECT * FROM notes WHERE id = '{$_GET['id']}'");
$stmt->execute();
$note = $stmt->fetch();
if (!$note) die('Note not found');
$note_data = json_decode(file_get_contents($note['path']), true);
if (!$note_data) die('Note not found');

if (!$note_data['is_public'] && (!is_logged_in() || $note_data['owner'] !== $_SESSION['username'])) {
    die('Permission denied');
}
?>
<!DOCTYPE html>
<html>

<head>
    <title><?= e($note_data['title']); ?></title>
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.min.css">
    <script src="https://cdn.jsdelivr.net/npm/markdown-it/dist/markdown-it.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/dompurify/dist/purify.min.js"></script>
    <style>
        header {
            padding: .5rem !important;
        }
    </style>
</head>

<body>
    <header>
        <b><?= e($note_data['title']); ?></b>・
        <span><?= $note_data['is_public'] ? '🌐' : '🔒'; ?></span>
    </header>
    <p></p>

    <script src="/app.js"></script>
    <script>
        document.querySelector('p').innerHTML = render(<?= json_encode($note_data['content']); ?>);
    </script>
</body>

</html>