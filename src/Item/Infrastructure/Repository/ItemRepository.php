<?php

namespace App\Item\Infrastructure\Repository;

use App\Item\Domain\Entity\Item;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Connection;

class ItemRepository
{
    private EntityManagerInterface $entityManager;
    private Connection $connection;

    public function __construct(EntityManagerInterface $entityManager, Connection $connection)
    {
        $this->entityManager = $entityManager;
        $this->connection = $connection;
    }

    public function findAll(): array
    {
        $items = $this->entityManager->getRepository(Item::class)->findAll();
        return array_map(fn(Item $item) => $item->serialize(), $items);
    }

    public function save(Item $item): void
    {
        $this->entityManager->persist($item);
        $this->entityManager->flush();
    }

    public function findById(int $id): ?Item
    {
        return $this->entityManager->getRepository(Item::class)->find($id);
    }

    public function find(int $id): ?Item
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder
            ->select('*')
            ->from('items')
            ->where('id = :id')
            ->setParameter('id', $id);
        
        $result = $queryBuilder->executeQuery()->fetchAssociative();

        if (!$result) {
            return null;
        }

        return new Item(
            $result['id'],
            $result['name']
        );
    }

}