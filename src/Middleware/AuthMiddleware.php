<?php

declare(strict_types=1);

namespace Payment_API\Middleware;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Slim\Psr7\Response as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;
use Payment_API\Utils\Response\Status_401 as Status_401;
use Payment_API\Enums\MiddlewareResponseTitle as ResponseTitle;

class AuthMiddleware
{
    use Status_401;

    public function __invoke(Request $request, Handler $handler): Response
    {
        $secretKey = $_ENV['JWT_SECRET_KEY'];

        $authToken = $request->getHeaderLine('Authorization');
        $authToken = str_replace('Bearer ', '', $authToken);

        try {
            $decodedToken = JWT::decode($authToken, new Key($secretKey, 'HS256'));
            return $handler->handle($request);
        } catch (\Exception $e) {
            return $this->status_401(
                ResponseTitle::UNAUTHORIZED,
                'Cannot retrieve resource.',
                'Kindly provide a valid bearer token.'
            );
        }
    }
}
