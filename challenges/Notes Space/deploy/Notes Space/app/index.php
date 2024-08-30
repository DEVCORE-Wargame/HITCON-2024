<?php
require_once __DIR__ . '/.includes.php';

function fetch_notes(PDO $db): PDOStatement
{
    $stmt = $db->prepare("SELECT * FROM notes WHERE owner = '{$_SESSION['username']}' ORDER BY created_at DESC");
    $stmt->execute();
    return $stmt;
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Notes</title>
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.min.css">
    <script src="https://cdn.jsdelivr.net/npm/markdown-it/dist/markdown-it.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/dompurify/dist/purify.min.js"></script>

    <style>
        .editor {
            display: flex;
            width: 100vw;
        }

        #content {
            width: 50%;
            font-size: 16px;
        }

        #preview {
            width: 50%;
            border: 1px solid #ccc;
            padding: 10px;
        }

        /* horizontal scrollable list */
        .notes {
            flex-wrap: wrap;

            /* horizontal scroll */
            overflow-x: scroll;
            white-space: nowrap;

            &>a {
                display: inline-block;
                padding: 10px;
                margin: 5px;
                border: 1px solid #ccc;
                border-radius: 5px;
                text-decoration: none;
                color: #333;

                &:hover {
                    background-color: #f0f0f0;
                }
            }

        }
    </style>
</head>

<body>
    <header>
        <nav>
            <a href="/">Home</a>
            <a href="//blog.md-notes.space">Blog</a>
            <a href="//about.md-notes.space">About</a>
        </nav>
        <h1>Note Space</h1>
        <?php if (is_logged_in()): ?>
            <p>Hello, @<?= e($_SESSION['username']); ?></p>
            <?php if ($_SESSION['username'] === 'admin'): ?>
                <p>FLAG: <?= getenv('FLAG'); ?></p>
            <?php endif; ?>
        <?php endif; ?>
    </header>
    <?php if (is_logged_in()): ?>
        <h3>Your notes</h3>
        <div class="notes">
            <?php foreach (fetch_notes($db) as $note): ?>
                <a href="/view.php?id=<?= e($note['id']); ?>"><?= e($note['title']); ?></a>
            <?php endforeach; ?>
        </div>
        <h3>Create a new note</h3>
        <form method="post" action="/create.php">
            <!-- markdown editor -->
            <div style="position: absolute; left: 0;">
                <div class="editor">
                    <textarea id="content" name="content" placeholder="# Title&#10;&#10;Content..." rows="10"></textarea>
                    <div id="preview"></div>
                </div>
                <p><label for="is_public">Public
                        <input type="checkbox" id="is_public" name="is_public" value="1"></label>
                </p>
                <input type="submit" value="Create">
            </div>
            <?= csrf_field() ?>

        </form>
    <?php else: ?>
        <h3>Login / Register</h3>
        <form method="post" action="login.php">
            <label for="username">Username
                <input type="text" id="username" name="username">
            </label>
            <label for="password">Password
                <input type="password" id="password" name="password">
            </label>
            <?= csrf_field() ?>
            <button>Login</button>
        </form>
    <?php endif; ?>

    <script src="/app.js"></script>
    <script>
        const content = document.getElementById('content');
        const preview = document.getElementById('preview');

        content.addEventListener('input', () => {
            preview.innerHTML = render(content.value);
        });
    </script>
</body>

</html>