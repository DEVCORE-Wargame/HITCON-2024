<?php
require_once 'parsedown.php';
define("POST_REGEX", "/([0-9]{4}-[0-9]{2}-[0-9]{2})-(.*).md/");

header("Content-Security-Policy: default-src 'self'; style-src 'unsafe-inline' https://cdn.simplecss.org/simple.min.css; img-src *;");

function toTitle($string)
{
    $string = str_replace('-', ' ', $string);
    $string = ucwords($string);
    return $string;
}


$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$md_path = ltrim($path, "/") . ".md";
if (preg_match(POST_REGEX, $md_path) && file_exists($md_path)) {
    $parsedown = new Parsedown();
    $content = file_get_contents($md_path);
    $html = $parsedown->text($content);
} else if ($path !== "/") {
    header("Content-Type: text/plain");
    die("error: '$path' not found.");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.min.css">
    <title>Blog</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        .markdown {
            margin-top: 2rem;
        }

        .card {
            padding: 0rem 2rem;
            margin: 1rem;
            border: 1px solid #ccc;
            border-radius: 5px;
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .card:hover {
            background-color: #f9f9f9;
        }
    </style>
</head>

<body>
    <?php if (isset($html)) : ?>
        <article class="markdown">
            <?= $html ?>
        </article>
    <?php else : ?>
        <h1>Blog</h1>
        <?php
        $posts = glob("posts/*.md");
        rsort($posts);
        foreach ($posts as $post) {
            preg_match(POST_REGEX, basename($post), $matches);
            $date = $matches[1];
            $title = $matches[2];
        ?>
            <a href="<?= substr($post, 0, -3) ?>" class="card">
                <h3><?= toTitle($title) ?></h3>
                <p><?= $date ?></p>
            </a>
        <?php } ?>
    <?php endif; ?>
</body>

</html>