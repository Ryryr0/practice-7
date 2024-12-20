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

header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: 0');

if (!isset($_SERVER['PHP_AUTH_USER'])) {
    header('WWW-Authenticate: Basic realm="Restricted Area"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Authentication required';
    exit;
} else {
    $stmt = $db->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->execute([$_SERVER['PHP_AUTH_USER']]);
    $user = $stmt->fetch();

    if ($user && $_SERVER['PHP_AUTH_PW'] === $user['password']) {
        echo 'Access granted';
        $_SESSION['authenticated'] = true;
        header("Location: /apache/src/admin/admin_page.php");
        session_start();

        exit;
    } else {
        header('HTTP/1.0 401 Unauthorized');
        echo 'Invalid credentials';
        exit;
    }
}
?>
