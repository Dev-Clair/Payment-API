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
class PaymentsEntity implements EntityInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id; // Payment ID (Autogenerated)

    #[ORM\Column(type: 'string', nullable: false, length: 150)]
    private string $upid; // Unique Payment Identifier

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private float $amount; // Total Amount Paid

    #[ORM\Column(type: 'datetime', nullable: false, updatable: true)]
    private DateTime $created_at; // Date and Time of Payment

    #[ORM\Column(type: 'string', nullable: false, columnDefinition: 'ENUM("paid", "pending", "invalid", "failed")')]
    private PaymentsStatus $status; // Payment Status

    #[ORM\Column(type: 'string', nullable: false, columnDefinition: 'ENUM("credit", "debit")')]
    private PaymentsType $type; // Payment Type

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
