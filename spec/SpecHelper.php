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
