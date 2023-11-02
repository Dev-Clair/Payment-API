<?php

declare(strict_types=1);

namespace Payment_API\Controller;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;
use stdClass;

class AuthController
{
    private string $jwtSecretKey;
    private string $jwtIssueTime;
    private string $jwtExpiryTime;

    function __construct(string $jwtSecretKey)
    {
        date_default_timezone_set('Europe/Munich');
        $this->jwtIssueTime = time();

        $this->jwtExpiryTime = $this->jwtIssueTime + 900;

        $this->jwtSecretKey = $jwtSecretKey;
    }

    public function encode(string $issuer, mixed $payload): string
    {

        $authToken = [
            "iss" => $issuer,
            "aud" => $issuer,
            "iat" => $this->jwtIssueTime,
            "exp" => $this->jwtExpiryTime,
            "payload" => $payload
        ];

        return JWT::encode($authToken, $this->jwtSecretKey, 'HS256');
    }

    public function decode(string $authToken): stdClass|Exception
    {
        try {
            $decode = JWT::decode($authToken, new Key($this->jwtSecretKey, 'HS256'));
            return $decode->data;
        } catch (Exception $e) {
            return $e;
        }
    }
}
