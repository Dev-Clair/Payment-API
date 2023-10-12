<?php

declare(strict_types=1);

namespace Payment_API\Trait;

use Fig\Http\Message\StatusCodeInterface;
use Slim\Psr7\Response as Response;

trait Response_400_Trait
{
    public function response_400(array|string $message, array|string|bool|null $data): Response
    {
        $status = [
            'status' =>   "Bad Request",
            'message' => $message,
            'data' => $data ?? null
        ];

        $response = new Response(StatusCodeInterface::STATUS_BAD_REQUEST);

        $response->getBody()->write(json_encode($status, JSON_PRETTY_PRINT));

        return $response->withHeader('Content-Type', 'application/json; charset=UTF-8');
    }
}
