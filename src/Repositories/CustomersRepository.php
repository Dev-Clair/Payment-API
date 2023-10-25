<?php

declare(strict_types=1);

namespace Payment_API\Repositories;

use Doctrine\ORM\EntityManager;
use Payment_API\Interface\RepositoryInterface;
use Payment_API\Entity\CustomersEntity;

class CustomersRepository implements RepositoryInterface
{
    public function __construct(protected EntityManager $entityManager)
    {
    }

    public function findAll(): array
    {
        return $this->entityManager->getRepository(CustomersEntity::class)->findAll();
    }

    public function findById(int $id): CustomersEntity|null
    {
        return $this->entityManager->getRepository(CustomersEntity::class)->find($id);
    }

    public function validateId(int $id): bool
    {
        return (bool) $this->findById($id);
    }

    public function store(CustomersEntity $customersEntity): void
    {
        $this->entityManager->persist($customersEntity);
        $this->entityManager->flush();
    }

    public function update(CustomersEntity $customersEntity): void
    {
        $this->entityManager->persist($customersEntity);
        $this->entityManager->flush();
    }

    public function remove(CustomersEntity $customersEntity): void
    {
        $this->entityManager->persist($customersEntity);
        $this->entityManager->flush();
    }
}
