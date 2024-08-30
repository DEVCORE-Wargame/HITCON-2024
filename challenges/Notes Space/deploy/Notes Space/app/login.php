<?php
require_once __DIR__ . '/.includes.php';

if (is_logged_in()) {
    redirect('/');
}


if (!isset($_POST['username']) || !isset($_POST['password'])) {
    die('Username and password are required');
}

if (!preg_match('/^[a-zA-Z0-9_]+$/', $_POST['username'])) {
    die("Username `{$_POST['username']}` contains invalid characters");
}

if (strlen($_POST['username']) < 4 || strlen($_POST['username']) > 16) {
    die("Username must be between 4 and 16 characters");
}

$stmt = $db->prepare("SELECT * FROM users WHERE username = '{$_POST['username']}'");
$stmt->execute();
$user = $stmt->fetch();

if ($user) {
    if (password_verify($_POST['password'], $user['password'])) {
        $_SESSION['username'] = $user['username'];
        redirect('/');
    } else {
        die("Invalid password for user {$_POST['username']}");
    }
} else {
    // automatically create a new user if it doesn't exist
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $stmt = $db->prepare("INSERT INTO users (username, password) VALUES ('$username', '$password')");
    $stmt->execute();
    $_SESSION['username'] = $username;
    redirect('/');
}



