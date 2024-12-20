<?php
require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/Controllers/AuthController.php';
require_once __DIR__ . '/Controllers/AdminController.php';
require_once __DIR__ . '/Models/Admin.php';
require_once __DIR__ . '/Models/User.php';
require_once __DIR__ . '/Controllers/AuthUserController.php';
require_once __DIR__ . '/Controllers/FileController.php';
require_once __DIR__ . '/Models/FileStorage.php';
require_once __DIR__ . '/Controllers/UserPreferencesController.php';
require_once __DIR__ . '/Models/UserPreferences.php';
require_once __DIR__ . '/Models/DataGenerator.php';
require_once __DIR__ . '/Controllers/StatisticsController.php';

use Controllers\AuthController;
use Controllers\AdminController;
use Controllers\AuthUserController;
use Controllers\FileController;
use Models\FileStorage;
use Controllers\UserPreferencesController;
use Models\UserPreferences;
// use Models\DataGenerator;
// use Controllers\StatisticsController;

$path = $_GET['path'] ?? '';


switch ($path) {
    case 'auth':
        $authController = new AuthController(getDbConnection());
        $authController->authenticate();
        break;

    case 'admin':
        $adminController = new AdminController(getDbConnection());
        $adminController->showAdminPage();
        break;

    case 'auth_user':
        $controller = new AuthUserController(getDbConnection());
        $controller->authenticate();
        break;

    case 'file':
        $redis = new Redis();
        $redis->connect('redis_db', 6379);
        $fileStorage = new FileStorage($redis);
        $controller = new FileController($fileStorage);
        $username = $_SESSION['username'] ?? 'guest';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->uploadPdf($username);
        } elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $controller->downloadPdf($username);
        }
        break;

    case 'prac4':
        $redis = new Redis();
        $redis->connect('redis_db', 6379);
        $preferences = new UserPreferences($redis);
        $controller = new UserPreferencesController($preferences);
        $controller->handleRequest();
        break;

    case 'statistic':
        $cities = [
            'New York', 'Los Angeles', 'Chicago', 'Houston', 'Phoenix', 
            'Philadelphia', 'San Antonio', 'San Diego', 'Dallas', 'San Jose'
        ];
        
        $fixtures = DataGenerator::generateFixtures(100, $cities);
        $controller = new StatisticsController($fixtures);
        $controller->generateCharts();
    default:
        echo 'Page not found';
        http_response_code(404);
}
?>
