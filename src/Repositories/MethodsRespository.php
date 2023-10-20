<?php

declare(strict_types=1);

namespace Payment_API\Repositories;

use Doctrine\ORM\EntityManager;
use Payment_API\Interface\RepositoryInterface;
use Payment_API\Entity\MethodsEntity;

class MethodsRepository implements RepositoryInterface
{
    public function __construct(protected EntityManager $entityManager)
    {
    }

    public function retrieveAll()
    {
        $this->entityManager->getRepository(MethodsEntity::class)->findAll();
    }

    public function retrieveById(int $id)
    {
        $this->entityManager->getRepository(MethodsEntity::class)->find($id);
    }

    public function store(MethodsEntity $methodsEntity)
    {
        $this->entityManager->persist($methodsEntity);
        $this->entityManager->flush();
    }

    public function update(MethodsEntity $methodsEntity)
    {
        $this->entityManager->persist($methodsEntity);
        $this->entityManager->flush();
    }

    public function remove(MethodsEntity $methodsEntity)
    {
        $this->entityManager->persist($methodsEntity);
        $this->entityManager->flush();
    }
}
