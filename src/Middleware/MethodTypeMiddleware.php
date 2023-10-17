<?php

declare(strict_types=1);

namespace Payment_API\Middleware;

use Slim\Psr7\Response as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;
use Payment_API\Utils\Trait\Response_405_Trait as Response_405;

class MethodTypeMiddleware
{
    use Response_405;

    protected array $allowedMethods;

    public function __construct(array $allowedMethods)
    {
        $this->allowedMethods = $allowedMethods;
    }

    public function __invoke(Request $request, Handler $handler): Response
    {
        $methodType = $request->getMethod();

        if (!in_array($methodType, $this->allowedMethods)) {

            return $this->response_405(
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
