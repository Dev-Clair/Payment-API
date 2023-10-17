<?php

declare(strict_types=1);

namespace Payment_API\Enums;

use Payment_API\Contracts\EnumsContract;

enum CustomersResponseTitle: string implements EnumsContract
{
    case GET = "Customers";
    case POST = "Create a New Customer Account";
    case PUT = "Modify Details of an Existing Customer Account";
    case DELETE = "Delete a Customer Account";
    case DEACTIVATE = "Deactivate a Customer Account";
    case REACTIVATE = "Reactivate a Customer Account";
}
