<?php

declare(strict_types=1);

namespace Payment_API\Enums;

use Payment_API\Contracts\EnumsContract;

enum PaymentStatus: string implements EnumsContract
{
    case PAID = "paid";
    case PENDING = "pending";
    case INVALID = "invalid";
    case FAILED = "failed";
}
