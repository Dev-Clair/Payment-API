<?php

use Firebase\JWT\JWT;

require_once __DIR__ . '/container.php';

$jwtPayload = [];

$jwtSecretKey = $_ENV['JWT_SECRET_KEY'];
$authToken = JWT::encode($jwtPayload, (string) $jwtSecretKey, 'HS256');

$response = [
    'token' => $authToken,
];

header('Content-Type: application/json');
echo json_encode($response);
