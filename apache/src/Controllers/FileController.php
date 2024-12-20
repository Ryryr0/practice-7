<?php

namespace Controllers;

use Models\FileStorage;

class FileController
{
    private FileStorage $fileStorage;

    public function __construct(FileStorage $fileStorage)
    {
        $this->fileStorage = $fileStorage;
    }

    public function uploadPdf(string $username): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['pdf_file'])) {
            $file = $_FILES['pdf_file'];

            if ($file['type'] !== 'application/pdf') {
                echo json_encode(['error' => 'Uploaded file is not a PDF.']);
                exit;
            }

            $fileContent = file_get_contents($file['tmp_name']);
            $filename = basename($file['name']);

            $this->fileStorage->savePdf($username, $filename, $fileContent);

            echo json_encode(['message' => 'File uploaded successfully.', 'filename' => $filename]);
            exit;
        }
    }

    public function downloadPdf(string $username): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['filename'])) {
            $filename = $_GET['filename'];
            $fileContent = $this->fileStorage->getPdf($username, $filename);

            if (!$fileContent) {
                echo json_encode(['error' => 'File not found.']);
                exit;
            }

            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            echo $fileContent;
            exit;
        }
        elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
            include __DIR__ . "/../Views/file_pdf.php";
        }
    }
}
