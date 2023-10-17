<?php

declare(strict_types=1);

namespace Payment_API\Enums;

use Payment_API\Contracts\EnumsContract;

enum MethodsResponseTitle: string implements EnumsContract
{
    case GET = "Payment Methods";
    case POST = "Create a New Payment Method";
    case PUT = "Modify an Existing Payment Method";
    case DELETE = "Delete a Payment Method";
    case DEACTIVATE = "Deactivate a Payment Method";
    case REACTIVATE = "Reactivate a Payment Method";
}
