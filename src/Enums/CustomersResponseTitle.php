<?php

declare(strict_types=1);

namespace Payment_API\Enums;

use Payment_API\Interface\EnumsInterface;

enum CustomersResponseTitle: string implements EnumsInterface
{
    case GET = "All Customers";
    case POST = "Create New Customer Account";
    case PUT = "Modify Existing Customer Account";
    case DELETE = "Delete Customer Account";
    case DEACTIVATE = "Deactivate Customer Account";
    case REACTIVATE = "Reactivate Customer Account";
}
