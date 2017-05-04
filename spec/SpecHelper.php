<?php
include 'vendor/autoload.php';

use Dotenv\Dotenv;
use GuzzleHttp\Client as GuzzleClient;

if (file_exists(__DIR__ . '/.env')) {
    $dotenv = new Dotenv(__DIR__);
    $dotenv->load();
}

function config($key) {
    
    if ($key == 'API_USER_KEY') {
        return getUserToken();
    }
    
    $value = getenv($key);

    if (!$value) {
        die('Environment varirable ' . $key . ' not set. Please complete spec/.env file.' . PHP_EOL);
    }

    return $value;
}

function getCourseData($name) {
    $data = ['name' => $name, 'city' => 'London'];

    for ($i=1; $i<=18; $i++) {
        $data['holes'][] = [
            'hole_number' => $i,
            'par' => rand(1,6),
            'stroke_index' => $i,
            'distance' => rand(1,999),
        ];
    }

    return $data;
}

function getUserToken() {
    $client = new GuzzleClient(['http_errors' => false]);
    
    $response = $client->request('POST', config('AUTH_SERVER') . '/oauth/token', [
        'form_params' => [
            'grant_type' => 'password',
            'username' => config('USERNAME'),
            'password' => config('PASSWORD'),
            'client_id' => config('PASSWORD_GRANT_CLIENT_ID'),
            'client_secret' => config('PASSWORD_GRANT_CLIENT_SECRET'),
            'scope' => 'user',
        ]], false);

    if ($response->getStatusCode() == 200 ){
        $jsonDecode = json_decode($response->getBody());
        return $jsonDecode->access_token;
    }
    
    return false;
}
