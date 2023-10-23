<?php

declare(strict_types=1);

namespace Payment_API\Enums;

use Payment_API\Interface\EnumsInterface;

enum MethodStatus: string implements EnumsInterface
{
    case ACTIVE = "active";
    case INACTIVE = "inactive";
}
