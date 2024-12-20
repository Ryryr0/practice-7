<?php
namespace Controllers;

use Models\User;

class AdminController {
    private $userModel;

    public function __construct($db) {
        $this->userModel = new User($db);
    }

    public function showAdminPage() {
        if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
            header("Location: /apache/src/index.php/path=auth");
            exit;
        }

        $users = $this->userModel->getAllUsers();
        include __DIR__ . "/../Views/admin_page.php";
    }
}
?>
