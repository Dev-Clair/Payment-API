<?php

use Dotenv\Dotenv;
use Payment_API\Controller\AuthController;

require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->safeload();

$jwtSecretKey = $_ENV['JWT_SECRET_KEY'];

$authToken = (new AuthController($jwtSecretKey))->encode('Payment-API', []);

$response = [
    'token' => $authToken,
    'expires' => "900 secs"
];

echo json_encode($response, JSON_PRETTY_PRINT);
