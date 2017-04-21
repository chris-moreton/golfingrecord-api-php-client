<?php
include 'vendor/autoload.php';

use Dotenv\Dotenv;

if (file_exists(__DIR__ . '/.env')) {
    $dotenv = new Dotenv(__DIR__);
    $dotenv->load();
}

function config($key) {
    $value = getenv($key);

    if (!$value) {
        die('Environment varirable ' . $key . ' not set. Please complete spec/.env file.' . PHP_EOL);
    }

    return $value;
}

function getCourseData($name) {
    $data = ['course_name' => $name, 'course_city' => 'London'];

    for ($i=1; $i<=18; $i++) {
        $data['holes'][] = [
            'hole_number' => $i,
            'par' => rand(1,6),
            'stroke_index' => $i,
            'yardage' => rand(1,999),
        ];
    }

    return $data;
}
