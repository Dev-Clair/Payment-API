<?php

declare(strict_types=1);

namespace Payment_API\Enums;

use Payment_API\Interface\EnumsInterface;

enum PaymentsResponseTitle: string implements EnumsInterface
{
    case GET = "Payment Records";
    case POST = "Create New Payment Record";
    case PUT = "Modify Existing Payment Record";
    case DELETE = "Delete Payment Record";
}
