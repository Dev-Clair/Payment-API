<?php

declare(strict_types=1);

namespace Payment_API\Model;

use Payment_API\Interface\ModelInterface;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Column;

#[Entity]
#[Table(name: 'payments')]
class PaymentsModel implements ModelInterface
{
}
