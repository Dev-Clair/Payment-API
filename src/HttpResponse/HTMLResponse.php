<?php

declare(strict_types=1);

namespace Payment_API\HttpResponse;

use Payment_API\Interface\EnumsInterface;
use Payment_API\Utils\Response\Status_200 as Status_200;
use Payment_API\Utils\Response\Status_201 as Status_201;
use Payment_API\Utils\Response\Status_400 as Status_400;
use Payment_API\Utils\Response\Status_401 as Status_401;
use Payment_API\Utils\Response\Status_404 as Status_404;
use Payment_API\Utils\Response\Status_405 as Status_405;
use Payment_API\Utils\Response\Status_422 as Status_422;
use Payment_API\Utils\Response\Status_500 as Status_500;
use Psr\Http\Message\ResponseInterface as Response;

class HTMLResponse
{
    use Status_200;
    use Status_201;
    use Status_400;
    use Status_401;
    use Status_404;
    use Status_405;
    use Status_422;
    use Status_500;

    public static function status_200(EnumsInterface $title, array|string $message, array|string|bool|null $resource): Response
    {
        return static::status_200(title: $title, message: $message, resource: $resource)
            ->withHeader('Content-Type', 'text/html; charset=UTF-8');
    }

    public static function status_201(EnumsInterface $title, array|string $message, array|string|bool|null $resource): Response
    {
        return static::status_201(title: $title, message: $message, resource: $resource)
            ->withHeader('Content-Type', 'text/html; charset=UTF-8');
    }

    public static function status_400(EnumsInterface $title, array|string $message, array|string|bool|null $resource): Response
    {
        return static::status_400(title: $title, message: $message, resource: $resource)
            ->withHeader('Content-Type', 'text/html; charset=UTF-8');
    }

    public static function status_401(EnumsInterface $title, array|string $message, array|string|bool|null $resource): Response
    {
        return static::status_401(title: $title, message: $message, resource: $resource)
            ->withHeader('Content-Type', 'text/html; charset=UTF-8');
    }

    public static function status_404(EnumsInterface $title, array|string $message, array|string|bool|null $resource): Response
    {
        return static::status_404(title: $title, message: $message, resource: $resource)
            ->withHeader('Content-Type', 'text/html; charset=UTF-8');
    }

    public static function status_405(EnumsInterface $title, array|string $message, array|string|bool|null $resource): Response
    {
        return static::status_405(title: $title, message: $message, resource: $resource)
            ->withHeader('Content-Type', 'text/html; charset=UTF-8');
    }

    public static function status_422(EnumsInterface $title, array|string $message, array|string|bool|null $resource): Response
    {
        return static::status_422(title: $title, message: $message, resource: $resource)
            ->withHeader('Content-Type', 'text/html; charset=UTF-8');
    }

    public static function status_500(EnumsInterface $title, array|string $message, array|string|bool|null $resource): Response
    {
        return static::status_500(title: $title, message: $message, resource: $resource)
            ->withHeader('Content-Type', 'text/html; charset=UTF-8');
    }
}
