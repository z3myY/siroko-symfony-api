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

    public function save(Order $order): void
    {
        $this->connection->insert('orders', [
            'user_id' => $order->userId()->value(),
            'total_price' => $order->totalPrice()->value()
        ]);
    }
}

