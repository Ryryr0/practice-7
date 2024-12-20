<?php

use GuzzleHttp\Client;

class ChartService
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function createChart($chartConfig)
    {
        $response = $this->client->post('https://quickchart.io/chart', [
            'json' => [
                'chart' => $chartConfig,
                'width' => 800,
                'height' => 400,
                'format' => 'png',
            ],
        ]);

        return $response->getBody()->getContents();
    }

    public function addWatermark($chartImage, string $watermarkText)
    {
        $image = imagecreatefromstring($chartImage);
        $width = imagesx($image);
        $height = imagesy($image);

        // Создание водяного знака
        $fontSize = 5; // Размер шрифта
        $textColor = imagecolorallocatealpha($image, 255, 255, 255, 75); // Белый цвет, прозрачный

        $textWidth = imagefontwidth($fontSize) * strlen($watermarkText);
        $textHeight = imagefontheight($fontSize);

        // Позиция для водяного знака (нижний правый угол)
        $x = $width - $textWidth - 10;
        $y = $height - $textHeight - 10;

        imagestring($image, $fontSize, $x, $y, $watermarkText, $textColor);

        // Сохранение изображения в память
        ob_start();
        imagepng($image);
        $watermarkedImage = ob_get_clean();

        imagedestroy($image);
        return $watermarkedImage;
    }
}