<?php
// session_start();

// // Подключение к Redis
// $redis = new Redis();
// $redis->connect('redis_db', 6379);

// // Обработка загрузки файла
// if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['pdf_file'])) {
//     $file = $_FILES['pdf_file'];

//     // Проверяем, что файл является PDF
//     if ($file['type'] !== 'application/pdf') {
//         echo json_encode(['error' => 'Uploaded file is not a PDF.']);
//         exit;
//     }

//     // Читаем содержимое файла
//     $fileContent = file_get_contents($file['tmp_name']);
//     $filename = basename($file['name']);

//     // Сохраняем файл в Redis
//     $username = $_SESSION['username'] ?? 'guest';
//     $redis->set("user:{$username}:pdf:{$filename}", $fileContent);

//     echo json_encode(['message' => 'File uploaded successfully.', 'filename' => $filename]);
//     exit;
// }

// // Обработка запроса на получение файла
// if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['filename'])) {
//     $filename = $_GET['filename'];
//     $username = $_SESSION['username'] ?? 'guest';

//     // Получаем файл из Redis
//     $fileContent = $redis->get("user:{$username}:pdf:{$filename}");

//     if ($fileContent === false) {
//         echo json_encode(['error' => 'File not found.']);
//         exit;
//     }

//     // Отправляем файл пользователю
//     header('Content-Type: application/pdf');
//     header('Content-Disposition: attachment; filename="' . $filename . '"');
//     echo $fileContent;
//     exit;
// }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDF Upload</title>
</head>
<body>
    <h1>Upload and Download PDF Files</h1>

    <form action="path=file" method="POST" enctype="multipart/form-data">
        <label for="pdf_file">Upload a PDF file:</label>
        <input type="file" id="pdf_file" name="pdf_file" accept="application/pdf" required>
        <button type="submit">Upload</button>
    </form>

    <form action="path=file" method="GET">
        <label for="filename">Download a PDF file by name:</label>
        <input type="text" id="filename" name="filename" placeholder="Enter filename" required>
        <button type="submit">Download</button>
    </form>
</body>
</html>
