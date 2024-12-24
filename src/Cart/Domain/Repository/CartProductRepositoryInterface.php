<?php

declare(strict_types=1);

namespace App\Cart\Domain\Repository;

use App\Cart\Domain\Entity\CartProduct;
use App\Shared\Domain\ValueObject\IntValueObject;

interface CartProductRepositoryInterface
{
    public function findById(IntValueObject $cartProductId): ?CartProduct;

    public function find(CartProduct $cartProduct): ?CartProduct;

    public function removeProduct(CartProduct $cartProduct): void;
}