<?php

declare(strict_types=1);

namespace App\Order\Infrastructure\Repository;

use Doctrine\DBAL\Connection;
use App\Order\Domain\Entity\Order;
use App\Order\Domain\Repository\OrderRepositoryInterface;

final class DbalOrderRepository implements OrderRepositoryInterface
{
    public function __construct(private Connection $connection)
    {
    }

    public function save(Order $order): int
    {
        $queryBuilder = $this->connection->createQueryBuilder();

        $queryBuilder->insert('orders')
            ->setValue('user_id', ':user_id')
            ->setValue('total_price', ':total_price')
            ->setParameter('user_id', $order->userId()->value())
            ->setParameter('total_price', $order->totalPrice()->value());

        $queryBuilder->executeStatement();

        return (int) $this->connection->lastInsertId();
    }
}

