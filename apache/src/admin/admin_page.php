<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
</head>
<body>
    <h1>Список пользователей</h1>
    <table>
    <?php
    session_start();

    if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
        header("Location: /apache/src/admin/auth.php");
        exit;
    }
    
    $mysqli = new mysqli("db", "user", 1234, "users_db");
    $users = $mysqli->query('select * from users');
    foreach ($users as $user){
        echo "<tr><td>{$user['name']}</td></tr>";
    }
    $mysqli->close();
    session_destroy();
    exit;
    ?>
    </table>
</body>
</html>