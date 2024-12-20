<?php
header("Content-Type: application/json");
include 'database.php';
$request_method = $_SERVER["REQUEST_METHOD"];

    // Получение списка игрушек
    function getToys() {
        global $pdo;
        $stmt = $pdo->query("SELECT * FROM toys");
        $toys = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($toys);
    }

    // Получение игрушки по ID
    function getToy($id) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM toys WHERE id = ?");
        $stmt->execute([$id]);
        $toy = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($toy) {
            echo json_encode($toy);
        } else {
            http_response_code(404);
            echo json_encode(["message" => "Toy not found."]);
        }
    }

    // Создание новой игрушки
    function createToy() {
        global $pdo;
        $data = json_decode(file_get_contents("php://input"), true);
        $stmt = $pdo->prepare("INSERT INTO toys (name, category_id, price, description) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$data['name'], $data['category_id'], $data['price'], $data['description']])) {
            http_response_code(201);
            echo json_encode(["message" => "Toy created successfully."]);
        } else {
            http_response_code(400);
            echo json_encode(["message" => "Failed to create toy."]);
        }
    }

    // Обновление игрушки
    function updateToy($id) {
        global $pdo;
        $data = json_decode(file_get_contents("php://input"), true);
        $stmt = $pdo->prepare("UPDATE toys SET name = ?, category_id = ?, price = ?, description = ? WHERE id = ?");
        if ($stmt->execute([$data['name'], $data['category_id'], $data['price'], $data['description'], $id])) {
            echo json_encode(["message" => "Toy updated successfully."]);
        } else {
            http_response_code(404);
            echo json_encode(["message" => "Failed to update toy."]);
        }
    }

    // Удаление игрушки
    function deleteToy($id) {
        global $pdo;
        $stmt = $pdo->prepare("DELETE FROM toys WHERE id = ?");
        if ($stmt->execute([$id])) {
            echo json_encode(["message" => "Toy deleted successfully."]);
        } else {
            http_response_code(404);
            echo json_encode(["message" => "Failed to delete toy."]);
        }
    }

// Обработка запросов
switch($request_method) {
    case 'GET':
        if (isset($_GET["id"])) {
            getToy($_GET["id"]);
        } else {
            getToys();
        }
        break;
    case 'POST':
        createToy();
        break;
    case 'PUT':
        if (isset($_GET["id"])) {
            updateToy($_GET["id"]);
        } else {
            http_response_code(400);
            echo json_encode(["message" => "ID is required for updating."]);
        }
        break;
    case 'DELETE':
        if (isset($_GET["id"])) {
            deleteToy($_GET["id"]);
        } else {
            http_response_code(400);
            echo json_encode(["message" => "ID is required for deletion."]);
        }
        break;
    default:
        http_response_code(405);
        echo json_encode(["message" => "Method not allowed."]);
        break;
}
?>
