<?php
header("Content-Type: application/json");
include 'database.php';

$request_method = $_SERVER["REQUEST_METHOD"];

// Получение списка категорий
function getCategories() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM categories");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($categories);
}

// Получение категории по ID
function getCategory($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
    $stmt->execute([$id]);
    $category = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($category) {
        echo json_encode($category);
    } else {
        http_response_code(404);
        echo json_encode(["message" => "Category not found."]);
    }
}

// Создание новой категории
function createCategory() {
    global $pdo;
    $data = json_decode(file_get_contents("php://input"), true);
    $stmt = $pdo->prepare("INSERT INTO categories (name) VALUES (?)");
    if ($stmt->execute([$data['name']])) {
        http_response_code(201);
        echo json_encode(["message" => "Category created successfully."]);
    } else {
        http_response_code(400);
        echo json_encode(["message" => "Failed to create category."]);
    }
}

// Обновление категории
function updateCategory($id) {
    global $pdo;
    $data = json_decode(file_get_contents("php://input"), true);
    $stmt = $pdo->prepare("UPDATE categories SET name = ? WHERE id = ?");
    if ($stmt->execute([$data['name'], $id])) {
        echo json_encode(["message" => "Category updated successfully."]);
    } else {
        http_response_code(404);
        echo json_encode(["message" => "Failed to update category."]);
    }
}

// Удаление категории
function deleteCategory($id) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
    if ($stmt->execute([$id])) {
        echo json_encode(["message" => "Category deleted successfully."]);
    } else {
        http_response_code(404);
        echo json_encode(["message" => "Failed to delete category."]);
    }
}

// Обработка запросов
switch($request_method) {
    case 'GET':
        if (isset($_GET["id"])) {
            getCategory($_GET["id"]);
        } else {
            getCategories();
        }
        break;
    case 'POST':
        createCategory();
        break;
    case 'PUT':
        if (isset($_GET["id"])) {
            updateCategory($_GET["id"]);
        } else {
            http_response_code(400);
            echo json_encode(["message" => "ID is required for updating."]);
        }
        break;
    case 'DELETE':
        if (isset($_GET["id"])) {
            deleteCategory($_GET["id"]);
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
