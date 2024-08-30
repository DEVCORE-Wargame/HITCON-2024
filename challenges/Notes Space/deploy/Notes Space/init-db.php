<?php

$db = new PDO('sqlite:/tmp/db.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$db->exec('CREATE TABLE IF NOT EXISTS users (username TEXT PRIMARY KEY, password TEXT)');
$db->exec("CREATE TABLE IF NOT EXISTS notes (
    id TEXT PRIMARY KEY,
    title TEXT,
    path TEXT,
    owner TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (owner) REFERENCES users(username)
)");


$ADMIN_PASSWORD = password_hash(getenv('ADMIN_PASSWORD'), PASSWORD_DEFAULT);

// create admin
$db->exec("INSERT OR IGNORE INTO users (username, password) VALUES ('admin', '$ADMIN_PASSWORD')");

// change permissions of the database file
chmod('/tmp/db.sqlite', 0666);
