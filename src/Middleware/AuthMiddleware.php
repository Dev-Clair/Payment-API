<?php

declare(strict_types=1);

namespace Payment_API\Middleware;

use Slim\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;
use Payment_API\Controller\AuthController;
use Payment_API\Enums\MiddlewareResponseTitle as ResponseTitle;
use Payment_API\Utils\Response\Status_401;

class AuthMiddleware
{
    use Status_401;

    private AuthController $authController;

    public function __construct(private string $jwtSecretKey)
    {
        $this->authController = new AuthController($this->jwtSecretKey);
    }

    public function __invoke(Request $request, Handler $handler): Response
    {
        $authToken = $this->extractAuthToken($request);

        try {
            $this->authController->decode($authToken);
            return $handler->handle($request);
        } catch (\Exception $e) {
            return $this->handleUnauthorizedResponse($e);
        }
    }

    private function extractAuthToken(Request $request): string
    {
        $authToken = $request->getHeaderLine('Authorization');
        return str_replace('Bearer ', '', $authToken);
    }

    private function handleUnauthorizedResponse(\Exception $e): Response
    {
        return $this->status_401(
            ResponseTitle::UNAUTHORIZED,
            ['Cannot retrieve resource' => $e->getMessage()],
            'Kindly provide a valid bearer token'
        );
    }
}
