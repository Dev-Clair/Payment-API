<?php

declare(strict_types=1);

namespace Payment_API\Enums;

use Payment_API\Contracts\EnumsContract;

enum PaymentsResponseTitle: string implements EnumsContract
{
    case GET = "Payment Records";
    case POST = "Create a New Payment Record";
    case PUT = "Modify an Existing Payment Record";
    case DELETE = "Delete a Payment Record";
}
