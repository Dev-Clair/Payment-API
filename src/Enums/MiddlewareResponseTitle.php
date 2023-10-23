<?php

declare(strict_types=1);

namespace Payment_API\Enums;

use Payment_API\Interface\EnumsInterface;

enum MiddlewareResponseTitle: string implements EnumsInterface
{
    case BAD_REQUEST = "Invalid Content Type";
    case NOT_ALLOWED = "Invalid Request Method";
}
