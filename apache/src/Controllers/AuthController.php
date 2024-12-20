<?php
namespace Controllers;

use Models\Admin;

class AuthController {
    private $adminModel;

    public function __construct($db) {
        $this->adminModel = new Admin($db);
    }

    public function authenticate() {
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Pragma: no-cache');
        header('Expires: 0');

        if (!isset($_SERVER['PHP_AUTH_USER'])) {
            header('WWW-Authenticate: Basic realm="Restricted Area"');
            header('HTTP/1.0 401 Unauthorized');
            echo 'Authentication required';
            exit;
        } else {
            $user = $this->adminModel->findByUsername($_SERVER['PHP_AUTH_USER']);
            if ($user && $_SERVER['PHP_AUTH_PW'] === $user['password']) {
                $_SESSION['authenticated'] = true;
                header("Location: /apache/src/index.php/path=admin");
                exit;
            } else {
                header('HTTP/1.0 401 Unauthorized');
                echo 'Invalid credentials';
                exit;
            }
        }
    }
}
?>
