<?php

declare(strict_types=1);

namespace Payment_API\Utils\Trait;

use Fig\Http\Message\StatusCodeInterface;
use Payment_API\Contracts\EnumsContract;
use Slim\Psr7\Response as Response;

trait Response_404_Trait
{
    public function response_404(EnumsContract $title, array|string $message, array|string|bool|null $resource): Response
    {
        $status = [
            'title' => $title,
            'status' => StatusCodeInterface::STATUS_NOT_FOUND,
            'message' => $message,
            'resource' => $resource ?? null
        ];

        $response = new Response(StatusCodeInterface::STATUS_NOT_FOUND);

        $response->getBody()->write(json_encode($status, JSON_PRETTY_PRINT));

        return $response;
    }
}
