<?php

declare(strict_types=1);

namespace Payment_API\Entity;

use DateTime;
use Payment_API\Interface\EntityInterface;
use Payment_API\Enums\PaymentsStatus;
use Payment_API\Enums\PaymentsType;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'payments')]
class CustomersEntity implements EntityInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', nullable: false, length: 150)]
    private string $upid;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private float $amount;

    #[ORM\Column(type: 'datetime', nullable: false, updatable: true)]
    private DateTime $created_at;

    #[ORM\Column(type: 'string', columnDefinition: 'ENUM("paid", "pending", "invalid", "failed")')]
    private PaymentsStatus $status;

    #[ORM\Column(type: 'string', columnDefinition: 'ENUM("card", "bank")')]
    private PaymentsType $type;

    public function __construct()
    {
        $this->amount = 0.00;
        $this->created_at = new DateTime('now');
    }

    public function getID(): int
    {
        return $this->id;
    }

    public function getUPID(): string
    {
        return $this->upid;
    }

    public function setUPID($upid): void
    {
        $this->upid = $upid;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount($amount): void
    {
        $this->amount = $amount;
    }

    public function getDateTime(): DateTime
    {
        return $this->created_at;
    }

    public function getStatus(): PaymentsStatus
    {
        return $this->status;
    }

    public function setStatus($status): void
    {
        $this->status = $status;
    }

    public function getType(): PaymentsType
    {
        return $this->type;
    }

    public function setType($type): void
    {
        $this->type = $type;
    }
}
