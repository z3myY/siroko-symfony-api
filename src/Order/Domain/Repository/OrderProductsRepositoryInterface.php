<?php

declare(strict_types=1);

namespace App\Order\Domain\Repository;

use App\Order\Domain\Entity\OrderProducts;

interface OrderProductsRepositoryInterface
{
    public function save(OrderProducts $orderProducts): void;
}