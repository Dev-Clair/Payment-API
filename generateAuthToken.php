<?php

use Dotenv\Dotenv;
use Payment_API\Controller\AuthController;

require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->safeload();

$issuer = 'Payment-API';

$payload = [];

$jwtSecretKey = $_ENV['JWT_SECRET_KEY'];

$authToken = (new AuthController($jwtSecretKey))->encode($issuer, $payload);

$response = [
    'token' => $authToken,
    'expiry' => "900 secs"
];

echo json_encode($response, JSON_PRETTY_PRINT);
