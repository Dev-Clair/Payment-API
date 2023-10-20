<?php

declare(strict_types=1);

namespace Payment_API\Repositories;

use Doctrine\ORM\EntityManager;
use Payment_API\Interface\RepositoryInterface;
use Payment_API\Entity\PaymentsEntity;

class PaymentsRepository implements RepositoryInterface
{
    public function __construct(protected EntityManager $entityManager)
    {
    }

    public function findAll(): array
    {
        return $this->entityManager->getRepository(PaymentsEntity::class)->findAll();
    }

    public function findById(int $id): PaymentsEntity|null
    {
        return $this->entityManager->getRepository(PaymentsEntity::class)->find($id);
    }

    public function store(PaymentsEntity $paymentsEntity): void
    {
        $this->entityManager->persist($paymentsEntity);
        $this->entityManager->flush();
    }

    public function update(PaymentsEntity $paymentsEntity): void
    {
        $this->entityManager->persist($paymentsEntity);
        $this->entityManager->flush();
    }

    public function remove(PaymentsEntity $paymentsEntity): void
    {
        $this->entityManager->persist($paymentsEntity);
        $this->entityManager->flush();
    }
}
