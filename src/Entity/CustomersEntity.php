<?php

declare(strict_types=1);

namespace Payment_API\Entity;

use DateTime;
use Payment_API\Interface\EntityInterface;
use Payment_API\Enums\CustomerStatus;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'customers')]
class CustomersEntity implements EntityInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id; // Customer ID (Autogenerated)

    #[ORM\Column(type: 'string', length: 20)]
    private string $ucid; // Unique Customer Identifier

    #[ORM\Column(type: 'string', nullable: false, updatable: true, length: 150)]
    private string $customer_name; // Customer Name

    #[ORM\Column(type: 'string', unique: true, updatable: true, length: 255)]
    private string $customer_email; // Customer Email

    #[ORM\Column(type: 'string', nullable: false, updatable: true, length: 11)]
    private string  $customer_phone; // Customer Phone

    #[ORM\Column(type: 'string', nullable: false, updatable: true, length: 255)]
    private string $customer_password; // Customer Password

    #[ORM\Column(type: 'string', nullable: true, updatable: true, length: 255)]
    private string $customer_address; // Customer Address

    #[ORM\Column(type: 'datetime', nullable: false, updatable: true)]
    private DateTime $created_at; // Date and Time of Account Creation

    #[ORM\Column(type: 'string', nullable: false, columnDefinition: 'ENUM("active", "inactive")')]
    private string $customer_status; // Customer Account Status

    #[ORM\Column(type: 'string', nullable: false, columnDefinition: 'ENUM("individual", "organization")')]
    private string $customer_type; // Customer Type

    public function __construct()
    {
        $this->created_at = new DateTime('now');
        $this->customer_status = CustomerStatus::ACTIVE->value;
    }

    public function getID(): int
    {
        return $this->id;
    }

    public function getUCID(): string
    {
        return $this->ucid;
    }

    public function setUCID(string $ucid): void
    {
        $this->ucid = $ucid;
    }

    public function getCustomerName(): string
    {
        return $this->customer_name;
    }

    public function setCustomerName(string $customer_name): void
    {
        $this->customer_name = $customer_name;
    }

    public function getCustomerEmail(): string
    {
        return $this->customer_email;
    }

    public function setCustomerEmail(string $customer_email): void
    {
        $this->customer_email = $customer_email;
    }

    public function getCustomerPhone(): string
    {
        return $this->customer_phone;
    }

    public function setCustomerPhone(string $customer_phone): void
    {
        $this->customer_phone = $customer_phone;
    }

    public function getCustomerPassword(): string
    {
        return $this->customer_password;
    }

    public function setCustomerPassword(string $customer_password): void
    {
        $this->customer_password = password_hash($customer_password, PASSWORD_BCRYPT);
    }

    public function getCustomerAddress(): string
    {
        return $this->customer_address;
    }

    public function setCustomerAddress(string $customer_address): void
    {
        $this->customer_address = $customer_address;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->created_at;
    }

    public function getCustomerStatus(): string
    {
        return $this->customer_status;
    }

    public function setCustomerStatus(string $customer_status): void
    {
        $this->customer_status = $customer_status;
    }

    public function getCustomerType(): string
    {
        return $this->customer_type;
    }

    public function setCustomerType(string $customer_type): void
    {
        $this->customer_type = $customer_type;
    }
}
