<?php

declare(strict_types=1);

namespace Payment_API\Enums;

use Payment_API\Interface\EnumsInterface;

enum PaymentType: string implements EnumsInterface
{
    case CREDIT = "credit";
    case DEBIT = "debit";
}
