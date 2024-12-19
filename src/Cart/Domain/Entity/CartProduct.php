<?php

declare(strict_types=1);

namespace App\Cart\Domain\Entity;

use App\Shared\Domain\ValueObject\IntValueObject;

/**
 * Class CartProduct
 * @package App\Cart\Domain\Entity
 */
final class CartProduct
{

    public function __construct(
        private IntValueObject $cartId,
        private IntValueObject $productId,
        private IntValueObject $quantity
    )
    {
    }

    public function cartId(): IntValueObject
    {
        return $this->cartId;
    }
    
    public function productId(): IntValueObject
    {
        return $this->productId;
    }

    public function quantity(): IntValueObject
    {
        return $this->quantity;
    }

    public function updateQuantity(IntValueObject $quantity): void
    {
        $this->quantity = $quantity;
    }

    public function increaseQuantity(IntValueObject $amount): void
    {
        $this->quantity = $this->quantity->incrementBy($amount->value());
    }

    public function decreaseQuantity(IntValueObject $amount): void
    {
        $this->quantity = $this->quantity->decrementBy($amount->value());
    }

    public function serialize(): array
    {
        return [
            'cartId' => $this->cartId->value(),
            'productId' => $this->productId->value(),
            'quantity' => $this->quantity->value()
        ];
    }   
}