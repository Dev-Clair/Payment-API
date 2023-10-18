<?php

declare(strict_types=1);

namespace Payment_API\Utils\Trait;

use Fig\Http\Message\StatusCodeInterface;
use Payment_API\Interface\EnumsInterface;
use Slim\Psr7\Response as Response;

trait Response_201_Trait
{
    public function response_201(EnumsInterface $title, array|string $message, array|string|bool|null $resource): Response
    {
        $status = [
            'title' => $title,
            'status' => StatusCodeInterface::STATUS_CREATED,
            'message' => $message,
            'resource' => $resource ?? null
        ];

        $response = new Response(StatusCodeInterface::STATUS_CREATED);

        $response->getBody()->write(json_encode($status, JSON_PRETTY_PRINT));

        return $response;
    }
}
