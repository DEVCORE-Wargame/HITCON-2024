<?php
define('NOTE_PATH', '/tmp/notes');

error_reporting(0);
ini_set('display_errors', '0');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Functions

function is_logged_in(): bool
{
    return isset($_SESSION['username']);
}

function redirect(string $url)
{
    header('Location: ' . $url);
    exit();
}

function login_required()
{
    if (!is_logged_in()) {
        redirect('/login.php');
    }
}

function e($text): string
{
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

function uuidv4(): string
{
    $data = random_bytes(16);
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

function is_uuidv4(string $uuid): bool
{
    return preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/', $uuid);
}

function csrf_field()
{
    return '<input type="hidden" name="csrf_token" value="' . $_SESSION['csrf_token'] . '">' . PHP_EOL;
}

function genenerate_csrf_token()
{
    $_SESSION['csrf_token'] = bin2hex(random_bytes(16));
    $_SESSION['csrf_from_addr'] = $_SERVER['HTTP_X_REMOTE_ADDR'];
}

// Database

$db = new PDO('sqlite:/tmp/db.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// CSRF protection

if (!isset($_SESSION['csrf_token'])) {
    genenerate_csrf_token();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token'])) {
        die('Missing CSRF token');
    }
    if ($_SESSION['csrf_token'] !== $_POST['csrf_token'] ||
	    $_SESSION['csrf_from_addr'] !== $_SERVER['HTTP_X_REMOTE_ADDR']) {
	    genenerate_csrf_token();
        die('Invalid CSRF token');
    }
    genenerate_csrf_token();
}
