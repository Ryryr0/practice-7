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

if (!isset($_SERVER['PHP_AUTH_USER'])) {
    header('WWW-Authenticate: Basic realm="Restricted Area"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Authentication required';
    exit;
} else {
    $stmt = $db->prepare("SELECT * FROM users WHERE name = ?");
    $stmt->execute([$_SERVER['PHP_AUTH_USER']]);
    $user = $stmt->fetch();

    if ($user && $_SERVER['PHP_AUTH_PW'] === $user['password']) {
        echo 'Access granted';
        $_SESSION['authenticated'] = true;
        // Устанавливаем примерные данные
        $_SESSION['username'] = $user['name'];  // Логин пользователя
        header("Location: /apache/src/users/prac4.php");
        exit;
    } else {
        header('HTTP/1.0 401 Unauthorized');
        echo 'Invalid credentials';
        session_unset();
        session_destroy();
        exit;
    }
}
?>