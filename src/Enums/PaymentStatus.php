<?php

declare(strict_types=1);

namespace Payment_API\Enums;

use Payment_API\Interface\EnumsInterface;

enum PaymentStatus: string implements EnumsInterface
{
    case PAID = "paid";
    case PENDING = "pending";
    case FAILED = "failed";
}
