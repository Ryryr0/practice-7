<?php
session_start();

$dsn = 'mysql:host=mysql_db;dbname=users_db';
$username = 'user';
$password = '1234';

try {
    $db = new PDO($dsn, $username, $password);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

function getDbConnection() {
    global $db;
    return $db;
}
?>
