<?php

declare(strict_types=1);

namespace Payment_API\Enums;

use Payment_API\Interface\EnumsInterface;

enum PaymentsType: string implements EnumsInterface
{
    case CREDIT = "credit";
    case DEBIT = "debit";
}
