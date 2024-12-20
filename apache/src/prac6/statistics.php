<?php
require '/var/www/html/vendor/autoload.php'; // Подключение Composer автозагрузки

use Faker\Factory as FakerFactory;
use GuzzleHttp\Client;

// === 1. Генерация фикстур ===
$faker = FakerFactory::create();
$fixtures = [];
$cities = [
    'New York', 'Los Angeles', 'Chicago', 'Houston', 'Phoenix', 
    'Philadelphia', 'San Antonio', 'San Diego', 'Dallas', 'San Jose'
];

// Генерируем записей с 5 полями каждая
for ($i = 0; $i < 100; $i++) {
    $fixtures[] = [
        'name' => $faker->name,
        'age' => $faker->numberBetween(0, 100),
        'salary' => $faker->numberBetween(20000, 100000),
        'position' => $faker->jobTitle,
        'city' => $cities[array_rand($cities)],
    ];
}

// === 2. Построение графиков ===
$client = new Client();
$charts = [];

// График 1: Средний возраст сотрудников по городам
$ageData = [];
foreach ($fixtures as $fixture) {
    $city = $fixture['city'];
    $ageData[$city][] = $fixture['age'];
}

$averageAgeByCity = [];
foreach ($ageData as $city => $ages) {
    $averageAgeByCity[$city] = array_sum($ages) / count($ages);
}


$charts[] = createChart($client, [
    'type' => 'bar',
    'data' => [
        'labels' => array_keys($averageAgeByCity),
        'datasets' => [[
            'label' => 'Средний возраст',
            'data' => array_values($averageAgeByCity)
        ]]
    ]
]);

// График 2: Количество сотрудников по городам
$cityCounts = array_count_values(array_column($fixtures, 'city'));
$cityCounts = array_slice($cityCounts, 0, 10);

$charts[] = createChart($client, [
    'type' => 'pie',
    'data' => [
        'labels' => array_keys($cityCounts),
        'datasets' => [[
            'label' => 'Сотруднико по городам',
            'data' => array_values($cityCounts)
        ]]
    ]
]);

// График 3: Распределение зарплат
$salaryData = [];
foreach ($fixtures as $fixture) {
    $city = $fixture['city'];
    $salaryData[$city][] = $fixture['salary'];
}

$averageSalaryByCity = [];
foreach ($salaryData as $city => $salaries) {
    $averageSalaryByCity[$city] = array_sum($salaries) / count($salaries);
}

$charts[] = createChart($client, [
    'type' => 'line',
    'data' => [
        'labels' => array_keys($averageSalaryByCity),
        'datasets' => [[
            'label' => 'Зарплаты',
            'data' => array_values($averageSalaryByCity)
        ]]
    ]
]);

// Функция для создания графика через QuickChart API
function createChart(Client $client, array $chartConfig) {
    $response = $client->post('https://quickchart.io/chart', [
        'json' => [
            'chart' => $chartConfig,
            'width' => 800,
            'height' => 400,
            'format' => 'png'
        ]
    ]);
    return $response->getBody()->getContents();
}

// === 3. Добавление водяного знака к графикам ===
$chartsWithWatermark = [];
foreach ($charts as $chart) {
    $chartsWithWatermark[] = addWatermark($chart);
}

// Функция для добавления водяного знака с использованием GD
function addWatermark($chartImage) {
    $image = imagecreatefromstring($chartImage);
    $width = imagesx($image);
    $height = imagesy($image);

    // Создание водяного знака
    $watermarkText = 'Korolev Artem';
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

// === 4. Отображение графиков на странице ===
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistics</title>
</head>
<body>
    <h1>Statistics</h1>
    <div>
        <?php foreach ($chartsWithWatermark as $chart): ?>
            <img src="data:image/png;base64,<?= base64_encode($chart) ?>" alt="Chart"  width="1000">
        <?php endforeach; ?>
    </div>
</body>
</html>
