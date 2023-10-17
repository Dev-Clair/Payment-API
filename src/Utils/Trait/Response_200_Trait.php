<?php

declare(strict_types=1);

namespace Payment_API\Utils\Trait;

use Fig\Http\Message\StatusCodeInterface;
use Slim\Psr7\Response as Response;

trait Response_200_Trait
{
    public function response_200(array|string $message, array|string|bool|null $data): Response
    {
        $status = [
            'status' =>   "OK",
            'message' => $message,
            'data' => $data
        ];

        $response = new Response(StatusCodeInterface::STATUS_OK);

        $response->getBody()->write(json_encode($status, JSON_PRETTY_PRINT));

        return $response->withHeader('Content-Type', 'application/json; charset=UTF-8');
    }
}
