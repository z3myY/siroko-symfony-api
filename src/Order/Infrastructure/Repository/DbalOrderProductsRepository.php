<?php

declare(strict_types=1);

namespace App\Order\Infrastructure\Repository;

use App\Order\Domain\Entity\OrderProducts;
use Doctrine\DBAL\Connection;
use App\Order\Domain\Repository\OrderProductsRepositoryInterface;

final class DbalOrderProductsRepository implements OrderProductsRepositoryInterface
{
    public function __construct(private Connection $connection)
    {
    }

    public function save(OrderProducts $orderProducts): void
    {
        $this->connection->insert('order_products', [
            'order_id' => $orderProducts->orderId()->value(),
            'product_id' => $orderProducts->productId()->value(),
            'name' => $orderProducts->name()->value(),
            'price' => $orderProducts->price()->value(),
            'quantity' => $orderProducts->quantity()->value()
        ]);
    }
}
