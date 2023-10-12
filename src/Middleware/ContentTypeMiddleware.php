<?php

declare(strict_types=1);

namespace Payment_API\Middleware;

use Slim\Psr7\Response as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;
use Payment_API\Trait\Response_400_Trait as Response_400;

class ContentTypeMiddleware
{
    use Response_400;

    public function __invoke(Request $request, Handler $handler): Response
    {
        $contentType = $request->getHeaderLine('Content-Type');

        if (strpos($contentType, 'application/json') === false) {

            return $this->response_400(
                'This endpoint does not allow the specified request method.',
                [
                    'supplied' => $contentType,
                    'required' => 'application/json; charset=UTF-8'
                ]
            );
        }

        return $handler->handle($request);
    }
}
