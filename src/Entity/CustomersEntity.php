<?php

declare(strict_types=1);

namespace Payment_API\Entity;

use DateTime;
use Payment_API\Interface\EntityInterface;
use Payment_API\Enums\CustomersStatus;
use Payment_API\Enums\CustomersType;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'customers')]
class CustomersEntity implements EntityInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 20)]
    private string $ucid;

    #[ORM\Column(type: 'string', nullable: false, length: 150)]
    private string $name;

    #[ORM\Column(type: 'string', unique: true, length: 255)]
    private string $email;

    #[ORM\Column(type: 'string', nullable: false, length: 255)]
    private string $password;

    #[ORM\Column(type: 'string', nullable: true, updatable: true, length: 255)]
    private string $address;

    #[ORM\Column(type: 'datetime', nullable: false, updatable: true)]
    private DateTime $created_at;

    #[ORM\Column(type: 'string', columnDefinition: 'ENUM("active", "inactive")')]
    private CustomersStatus $status;

    #[ORM\Column(type: 'string', columnDefinition: 'ENUM("individual", "organization")')]
    private CustomersType $type;

    public function __construct()
    {
        $this->created_at = new DateTime('now');
    }

    public function getID(): int
    {
        return $this->id;
    }

    public function getUCID(): string
    {
        return $this->ucid;
    }

    public function setUCID($ucid): void
    {
        $this->ucid = $ucid;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName($name): void
    {
        $this->name = $name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail($email): void
    {
        $this->email = $email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword($password): void
    {
        $this->password = hash(PASSWORD_BCRYPT, $password);
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function setAddress($address): void
    {
        $this->address = $address;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->created_at;
    }

    public function getStatus(): CustomersStatus
    {
        return $this->status;
    }

    public function setStatus($status): void
    {
        $this->status = $status;
    }

    public function getType(): CustomersType
    {
        return $this->type;
    }

    public function setType($type): void
    {
        $this->type = $type;
    }
}
