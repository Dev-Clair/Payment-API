<?php

declare(strict_types=1);

namespace Payment_API\Enums;

use Payment_API\Interface\EnumsInterface;

enum CustomersType: string implements EnumsInterface
{
    case INDIVIDUAL = "individual";
    case ORGANIZATION = "organization";
}
