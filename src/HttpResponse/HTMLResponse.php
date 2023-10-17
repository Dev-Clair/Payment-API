<?php

declare(strict_types=1);

namespace Payment_API\HttpResponse;

use Payment_API\Utils\Trait\Response_200_Trait as Response_200;
use Payment_API\Utils\Trait\Response_201_Trait as Response_201;
use Payment_API\Utils\Trait\Response_400_Trait as Response_400;
use Payment_API\Utils\Trait\Response_401_Trait as Response_401;
use Payment_API\Utils\Trait\Response_404_Trait as Response_404;
use Payment_API\Utils\Trait\Response_405_Trait as Response_405;
use Payment_API\Utils\Trait\Response_422_Trait as Response_422;
use Payment_API\Utils\Trait\Response_500_Trait as Response_500;
use Slim\Psr7\Response as Response;

class HTMLResponse
{
    use Response_200;
    use Response_201;
    use Response_400;
    use Response_401;
    use Response_404;
    use Response_405;
    use Response_422;
    use Response_500;

    public static function response_200(array|string $message, array|string|bool|null $resource): Response
    {
        return static::response_200(message: $message, resource: $resource)
            ->withHeader('Content-Type', 'application/html; charset=UTF-8');
    }

    public static function response_201(array|string $message, array|string|bool|null $resource): Response
    {
        return static::response_201(message: $message, resource: $resource)
            ->withHeader('Content-Type', 'application/html; charset=UTF-8');
    }

    public static function response_400(array|string $message, array|string|bool|null $resource): Response
    {
        return static::response_400(message: $message, resource: $resource)
            ->withHeader('Content-Type', 'application/html; charset=UTF-8');
    }

    public static function response_401(array|string $message, array|string|bool|null $resource): Response
    {
        return static::response_401(message: $message, resource: $resource)
            ->withHeader('Content-Type', 'application/html; charset=UTF-8');
    }

    public static function response_404(array|string $message, array|string|bool|null $resource): Response
    {
        return static::response_404(message: $message, resource: $resource)
            ->withHeader('Content-Type', 'application/html; charset=UTF-8');
    }

    public static function response_405(array|string $message, array|string|bool|null $resource): Response
    {
        return static::response_405(message: $message, resource: $resource)
            ->withHeader('Content-Type', 'application/html; charset=UTF-8');
    }

    public static function response_422(array|string $message, array|string|bool|null $resource): Response
    {
        return static::response_422(message: $message, resource: $resource)
            ->withHeader('Content-Type', 'application/html; charset=UTF-8');
    }

    public static function response_500(array|string $message, array|string|bool|null $resource): Response
    {
        return static::response_500(message: $message, resource: $resource)
            ->withHeader('Content-Type', 'application/html; charset=UTF-8');
    }
}
