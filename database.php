<?php
$databaseFile = 'system.db';

try {
    $db = new PDO('sqlite:' . $databaseFile);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Connection failed: ' . $e->getMessage());
}

$query = "CREATE TABLE IF NOT EXISTS admins (
    id INTEGER PRIMARY KEY,
    login TEXT,
    password TEXT,
    email VARCHAR(99),
    profilePicture BLOB
)";
$db->exec($query);

$query = "CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY,
    login TEXT,
    password TEXT,
    email VARCHAR(99),
    profilePicture BLOB
)";
$db->exec($query);

$query = "CREATE TABLE IF NOT EXISTS posts (
    id INTEGER PRIMARY KEY,
    userId INTEGER,
    opinion TEXT,
    title VARCHAR(40),
    starRating INTEGER,
    attachment BLOB,
    attitude DECIMAL(9,6),
    FOREIGN KEY (userId) REFERENCES users(id)
)";
$db->exec($query);

?>