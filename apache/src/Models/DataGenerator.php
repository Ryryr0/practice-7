<?php
require '/var/www/html/vendor/autoload.php'; // Подключение Composer автозагрузки

use Faker\Factory as FakerFactory;
use GuzzleHttp\Client;


class DataGenerator
{
    public static function generateFixtures(int $count, array $cities): array
    {
        $faker = FakerFactory::create();
        $fixtures = [];

        for ($i = 0; $i < $count; $i++) {
            $fixtures[] = [
                'name' => $faker->name,
                'age' => $faker->numberBetween(0, 100),
                'salary' => $faker->numberBetween(20000, 100000),
                'position' => $faker->jobTitle,
                'city' => $cities[array_rand($cities)],
            ];
        }

        return $fixtures;
    }
}