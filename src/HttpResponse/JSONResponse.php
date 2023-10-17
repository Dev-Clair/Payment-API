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

class JSONResponse
{
    use Response_200;
    use Response_201;
    use Response_400;
    use Response_401;
    use Response_404;
    use Response_405;
    use Response_422;
    use Response_500;

    public static function response_200(): Response
    {
        return static::response_200();
    }

    public static function response_201(): Response
    {
        return static::response_201();
    }

    public static function response_400(): Response
    {
        return static::response_400();
    }

    public static function response_401(): Response
    {
        return static::response_401();
    }

    public static function response_404(): Response
    {
        return static::response_404();
    }

    public static function response_405(): Response
    {
        return static::response_405();
    }

    public static function response_422(): Response
    {
        return static::response_422();
    }

    public static function response_500(): Response
    {
        return static::response_500();
    }
}
