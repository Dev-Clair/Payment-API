<?php

declare(strict_types=1);

namespace Payment_API\Utils\Response;

use Fig\Http\Message\StatusCodeInterface;
use Payment_API\Interface\EnumsInterface;
use Slim\Psr7\Response as Response;

trait Status_401
{
    public function status_401(EnumsInterface $title, array|string $message, object|array|string|bool|null $resource): Response
    {
        $status = [
            'title' => $title,
            'status' => StatusCodeInterface::STATUS_UNAUTHORIZED,
            'message' => $message,
            'resource' => $resource
        ];

        $response = new Response(StatusCodeInterface::STATUS_UNAUTHORIZED);

        $response->getBody()->write(json_encode($status, JSON_PRETTY_PRINT));

        return $response->withHeader('Content-Type', 'application/json; charset=UTF-8');
    }
}
