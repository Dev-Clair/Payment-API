<?php

declare(strict_types=1);

namespace Payment_API\Utils\Response;

use Fig\Http\Message\StatusCodeInterface;
use Payment_API\Interface\EnumsInterface;
use Slim\Psr7\Response as Response;

trait Status_400
{
    public function status_400(EnumsInterface $title, array|string $message, array|string|bool|null $resource): Response
    {
        $status = [
            'title' => $title,
            'status' => StatusCodeInterface::STATUS_BAD_REQUEST,
            'message' => $message,
            'resource' => $resource ?? null
        ];

        $response = new Response(StatusCodeInterface::STATUS_BAD_REQUEST);

        $response->getBody()->write(json_encode($status, JSON_PRETTY_PRINT));

        return $response->withHeader('Content-Type', 'application/json; charset=UTF-8');
    }
}
