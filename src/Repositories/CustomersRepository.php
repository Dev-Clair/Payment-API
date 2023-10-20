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

    public function retrieveAll()
    {
        $this->entityManager->getRepository(CustomersEntity::class)->findAll();
    }

    public function retrieveById(int $id)
    {
        $this->entityManager->getRepository(CustomersEntity::class)->findAll();
    }

    public function store(CustomersEntity $customersEntity)
    {
        $this->entityManager->persist($customersEntity);
        $this->entityManager->flush();
    }

    public function update(CustomersEntity $customersEntity)
    {
        $this->entityManager->persist($customersEntity);
        $this->entityManager->flush();
    }

    public function remove(CustomersEntity $customersEntity)
    {
        $this->entityManager->persist($customersEntity);
        $this->entityManager->flush();
    }
}
