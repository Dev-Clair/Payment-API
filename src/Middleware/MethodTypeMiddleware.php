<?php

declare(strict_types=1);

namespace Payment_API\Middleware;

use Slim\Psr7\Response as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;
use Payment_API\HttpResponse\JSONResponse;
use Payment_API\Enums\MiddlewareResponseTitle as ResponseTitle;

class MethodTypeMiddleware
{
    protected array $allowedMethods;

    public function __construct(array $allowedMethods)
    {
        $this->allowedMethods = $allowedMethods;
    }

    public function __invoke(Request $request, Handler $handler): Response
    {
        $methodType = $request->getMethod();

        if (!in_array($methodType, $this->allowedMethods)) {

            return JSONResponse::response_405(
                ResponseTitle::NOT_ALLOWED,
                'This endpoint does not allow the specified request method.',
                [
                    'supplied' => $methodType,
                    'allowed' => implode(",", $this->allowedMethods)
                ]
            );
        }

        return $handler->handle($request);
    }
}
