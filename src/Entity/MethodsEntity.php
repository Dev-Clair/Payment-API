<?php

declare(strict_types=1);

namespace Payment_API\Entity;

use DateTime;
use Payment_API\Interface\EntityInterface;
use Payment_API\Enums\MethodsStatus;
use Payment_API\Enums\MethodsType;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'methods')]
class MethodsEntity implements EntityInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', nullable: false, length: 150)]
    private string $name;

    #[ORM\Column(type: 'string', nullable: false, length: 20)]
    private string $umid;

    #[ORM\Column(type: 'datetime', nullable: false, updatable: true)]
    private DateTime $created_at;

    #[ORM\Column(type: 'string', columnDefinition: 'ENUM("active", "inactive")')]
    private MethodsStatus $status;

    #[ORM\Column(type: 'string', columnDefinition: 'ENUM("card", "bank")')]
    private MethodsType $type;

    public function __construct()
    {
        $this->created_at = new DateTime('now');
    }

    public function getID(): int
    {
        return $this->id;
    }

    public function getUMID(): string
    {
        return $this->umid;
    }

    public function setUMID($umid): void
    {
        $this->umid = $umid;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName($name): void
    {
        $this->name = $name;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->created_at;
    }

    public function getStatus(): MethodsStatus
    {
        return $this->status;
    }

    public function setStatus($status): void
    {
        $this->status = $status;
    }

    public function getType(): MethodsType
    {
        return $this->type;
    }

    public function setType($type): void
    {
        $this->type = $type;
    }
}
