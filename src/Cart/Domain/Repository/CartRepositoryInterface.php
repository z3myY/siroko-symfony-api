<?php

declare(strict_types=1);

namespace App\Cart\Domain\Repository;

use App\Cart\Domain\Entity\Cart;
use App\Cart\Domain\Entity\CartProduct;
use App\Shared\Domain\ValueObject\IntValueObject;

interface CartRepositoryInterface
{
    public function create(IntValueObject $userId): int;

    public function findById(IntValueObject $id): ?Cart;

    public function save(CartProduct $cartProduct): void;

    public function remove(IntValueObject $id): void;
}