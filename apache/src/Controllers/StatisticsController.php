<?php
require_once './Models/DataGenerator.php';
require_once './Controllers/ChartService.php';
use Models\DataGenerator;

class StatisticsController
{
    private array $fixtures;
    private ChartService $chartService;

    public function __construct(array $fixtures)
    {
        $this->fixtures = $fixtures;
        $this->chartService = new ChartService();
    }

    public function getAverageAgeByCity(): array
    {
        $ageData = [];
        foreach ($this->fixtures as $fixture) {
            $ageData[$fixture['city']][] = $fixture['age'];
        }

        $averageAgeByCity = [];
        foreach ($ageData as $city => $ages) {
            $averageAgeByCity[$city] = array_sum($ages) / count($ages);
        }

        return $averageAgeByCity;
    }

    public function getEmployeeCountsByCity(): array
    {
        return array_count_values(array_column($this->fixtures, 'city'));
    }

    public function getAverageSalaryByCity(): array
    {
        $salaryData = [];
        foreach ($this->fixtures as $fixture) {
            $salaryData[$fixture['city']][] = $fixture['salary'];
        }

        $averageSalaryByCity = [];
        foreach ($salaryData as $city => $salaries) {
            $averageSalaryByCity[$city] = array_sum($salaries) / count($salaries);
        }

        return $averageSalaryByCity;
    }

    public function generateCharts()
    {
        $charts = [];

        // График 1: Средний возраст сотрудников по городам
        $charts[] = $this->chartService->createChart([
            'type' => 'bar',
            'data' => [
                'labels' => array_keys($this->getAverageAgeByCity()),
                'datasets' => [[
                    'label' => 'Средний возраст',
                    'data' => array_values($this->getAverageAgeByCity()),
                ]],
            ],
        ]);

        // График 2: Количество сотрудников по городам
        $charts[] = $this->chartService->createChart([
            'type' => 'pie',
            'data' => [
                'labels' => array_keys($this->getEmployeeCountsByCity()),
                'datasets' => [[
                    'label' => 'Сотрудников по городам',
                    'data' => array_values($this->getEmployeeCountsByCity()),
                ]],
            ],
        ]);

        // График 3: Средняя зарплата по городам
        $charts[] = $this->chartService->createChart([
            'type' => 'line',
            'data' => [
                'labels' => array_keys($this->getAverageSalaryByCity()),
                'datasets' => [[
                    'label' => 'Зарплаты',
                    'data' => array_values($this->getAverageSalaryByCity()),
                ]],
            ],
        ]);

        // Добавляем водяные знаки
        $chartsWithWatermark = [];
        foreach ($charts as $chart) {
            $chartsWithWatermark[] = $this->chartService->addWatermark($chart, 'Korolev Artem');
        };
        include __DIR__ . "/../Views/statistics.php";
    }
}