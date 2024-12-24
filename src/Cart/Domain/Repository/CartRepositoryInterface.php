<?php

declare(strict_types=1);

namespace App\Cart\Domain\Repository;

use App\Cart\Domain\Entity\Cart;
use App\Cart\Domain\Entity\CartProduct;
use App\Shared\Domain\ValueObject\IntValueObject;

interface CartRepositoryInterface
{
    public function create(Cart $cart): int;

    public function findAll(): array;

    public function findById(IntValueObject $id): ?Cart;

    public function findByUserId(IntValueObject $userId): ?Cart;

    public function save(Cart $cartProduct): void;

    public function saveProduct(CartProduct $cartProduct): void;

    public function remove(IntValueObject $id): void;

    public function removeProduct(CartProduct $cartProduct): void;

    public function clear(Cart $cartProduct): void;

    public function lastCart(): ?Cart;
}