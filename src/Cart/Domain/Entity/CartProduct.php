<?php

declare(strict_types=1);

namespace App\Cart\Domain\Entity;

use App\Shared\Domain\ValueObject\FloatValueObject;
use App\Shared\Domain\ValueObject\IntValueObject;
use App\Shared\Domain\ValueObject\StringValueObject;

/**
 * Class CartProduct
 * @package App\Cart\Domain\Entity
 */
class CartProduct
{

    public function __construct(
        private IntValueObject $cartId,
        private IntValueObject $productId,
        private IntValueObject $quantity,
        private StringValueObject $name,
        private FloatValueObject $price
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

    public function name(): StringValueObject
    {
        return $this->name;
    }

    public function price(): FloatValueObject
    {
        return $this->price;
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

    public function totalPrice(): FloatValueObject
    {
        return FloatValueObject::from($this->quantity->value() * $this->price->value());
    }

    public function updateName(StringValueObject $name): void
    {
        $this->name = $name;
    }

    public function updatePrice(FloatValueObject $price): void
    {
        $this->price = $price;
    }

    public function update(IntValueObject $quantity, StringValueObject $name, FloatValueObject $price): void
    {
        $this->quantity = $quantity;
        $this->name = $name;
        $this->price = $price;
    }

    public static function load(
        IntValueObject $cartId,
        IntValueObject $productId,
        IntValueObject $quantity,
        StringValueObject $name,
        FloatValueObject $price
        ): self {
        return new self(
            cartId: $cartId,
            productId: $productId,
            quantity: $quantity,
            name: $name,
            price: $price
        );
    }

    public function serialize(): array
    {
        return [
            'cartId' => $this->cartId->value(),
            'productId' => $this->productId->value(),
            'quantity' => $this->quantity->value(),
            'name' => $this->name->value(),
            'price' => $this->price->value(),
        ];
    }   
}