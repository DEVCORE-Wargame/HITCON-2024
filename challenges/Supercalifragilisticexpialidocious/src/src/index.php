<?php

if (!isset($_GET['code'])) {
    echo '<h1>PHP Syntax Checker</h1><form method="GET"><textarea name="code"></textarea><br /><button type="submit">Submit</button></form><br />';
    highlight_file(__FILE__);
    exit();
}

$code = strval($_GET['code']);

try {
    create_function('', $code);
    echo "valid";
} catch (ParseError $e) {
    echo "syntax error";
}