<?php

declare(strict_types=1);

namespace Payment_API\Enums;

use Payment_API\Interface\EnumsInterface;

enum MethodType: string implements EnumsInterface
{
    case CARD = "card";
    case BANK = "bank";
}
