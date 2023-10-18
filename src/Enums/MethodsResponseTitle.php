<?php

declare(strict_types=1);

namespace Payment_API\Enums;

use Payment_API\Interface\EnumsInterface;

enum MethodsResponseTitle: string implements EnumsInterface
{
    case GET = "Payment Methods";
    case POST = "Create New Payment Method";
    case PUT = "Modify Existing Payment Method";
    case DELETE = "Delete Payment Method";
    case DEACTIVATE = "Deactivate Payment Method";
    case REACTIVATE = "Reactivate Payment Method";
}
