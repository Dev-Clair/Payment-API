<?php

declare(strict_types=1);

namespace Payment_API\Controller;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use stdClass;

class AuthController
{
    private string $jwtSecretKey;
    private int $jwtIssueTime;
    private int $jwtExpiryTime;

    const SECONDS = 900;

    function __construct(string $jwtSecretKey)
    {
        date_default_timezone_set('Europe/Munich');
        $this->jwtIssueTime = time();

        $this->jwtExpiryTime = $this->jwtIssueTime + static::SECONDS;

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

    public function decode(string $authToken): stdClass
    {
        return JWT::decode($authToken, new Key($this->jwtSecretKey, 'HS256'));
    }
}
