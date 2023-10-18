<?php

declare(strict_types=1);

namespace Payment_API\Utils\Trait;

use Fig\Http\Message\StatusCodeInterface;
use Payment_API\Interface\EnumsInterface;
use Slim\Psr7\Response as Response;

trait Response_405_Trait
{
    public function response_405(EnumsInterface $title, array|string $message, array|string|bool|null $resource): Response
    {
        $status = [
            'title' => $title,
            'status' => StatusCodeInterface::STATUS_METHOD_NOT_ALLOWED,
            'message' => $message,
            'resource' => $resource ?? null
        ];

        $response = new Response(StatusCodeInterface::STATUS_METHOD_NOT_ALLOWED);

        $response->getBody()->write(json_encode($status, JSON_PRETTY_PRINT));

        return $response;
    }
}
