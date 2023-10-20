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

    public function retrieveAll()
    {
        $this->entityManager->getRepository(PaymentsEntity::class)->findAll();
    }

    public function retrieveById(int $id)
    {
        $this->entityManager->getRepository(PaymentsEntity::class)->find($id);
    }

    public function store(PaymentsEntity $paymentsEntity)
    {
        $this->entityManager->persist($paymentsEntity);
        $this->entityManager->flush();
    }

    public function update(PaymentsEntity $paymentsEntity)
    {
        $this->entityManager->persist($paymentsEntity);
        $this->entityManager->flush();
    }

    public function remove(PaymentsEntity $paymentsEntity)
    {
        $this->entityManager->persist($paymentsEntity);
        $this->entityManager->flush();
    }
}
