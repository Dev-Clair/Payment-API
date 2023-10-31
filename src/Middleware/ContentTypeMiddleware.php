<?php

declare(strict_types=1);

namespace Payment_API\Middleware;

use Slim\Psr7\Response as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;
use Payment_API\HttpResponse\JSONResponse;
use Payment_API\Enums\MiddlewareResponseTitle as ResponseTitle;

class ContentTypeMiddleware
{
    protected string $allowedContentType;

    public function __construct(string $allowedContentType)
    {
        $this->allowedContentType = $allowedContentType;
    }

    public function __invoke(Request $request, Handler $handler): Response
    {
        $contentType = $request->getHeaderLine('Content-Type');

        if ($contentType !== $this->allowedContentType) {
            return (new JSONResponse)->status_400(
                ResponseTitle::BAD_REQUEST,
                'This endpoint does not allow the specified content type.',
                [
                    'supplied' => $contentType,
                    'required' => $this->allowedContentType
                ]
            );
        }

        return $handler->handle($request);
    }
}
