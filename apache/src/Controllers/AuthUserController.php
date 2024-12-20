<?php

namespace Controllers;

use Models\User;
use PDO;

class AuthUserController
{
    private User $userModel;

    public function __construct(PDO $db)
    {
        $this->userModel = new User($db);
    }

    public function authenticate(): void
    {
        session_start();

        if (!isset($_SERVER['PHP_AUTH_USER'])) {
            header('WWW-Authenticate: Basic realm="Restricted Area"');
            header('HTTP/1.0 401 Unauthorized');
            echo 'Authentication required';
            exit;
        }

        $user = $this->userModel->authenticate($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']);

        if ($user) {
            $_SESSION['authenticated'] = true;
            $_SESSION['username'] = $user['name'];
            header("Location: /apache/src/index.php/path=prac4");
        } else {
            header('HTTP/1.0 401 Unauthorized');
            echo 'Invalid credentials';
        }
        exit;
    }
}
