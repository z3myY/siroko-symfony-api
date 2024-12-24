<?php

declare(strict_types=1);

namespace App\Order\Domain\Entity;

use App\Shared\Domain\AggregateRoot;
use App\Shared\Domain\ValueObject\FloatValueObject;
use App\Shared\Domain\ValueObject\IntValueObject;
use App\Shared\Domain\ValueObject\StringValueObject;

class OrderProducts extends AggregateRoot
{
    public function __construct(
        private IntValueObject $orderId,
        private IntValueObject $productId,
        private StringValueObject $name,
        private FloatValueObject $price,
        private IntValueObject $quantity
    ) {
    }

    public function orderId(): IntValueObject
    {
        return $this->orderId;
    }

    public function productId(): IntValueObject
    {
        return $this->productId;
    }

    public function name(): StringValueObject
    {
        return $this->name;
    }

    public function price(): FloatValueObject
    {
        return $this->price;
    }

    public function quantity(): IntValueObject
    {
        return $this->quantity;
    }

    public function totalPrice(): FloatValueObject
    {
        return $this->price->multiplyBy($this->quantity->value());
    }

    public static function load(
        IntValueObject $orderId,
        IntValueObject $productId,
        StringValueObject $name,
        FloatValueObject $price,
        IntValueObject $quantity
    ): self {
        return new self(
            orderId: $orderId,
            productId: $productId,
            name: $name,
            price: $price,
            quantity: $quantity
        );
    }

    public function serialize(): array
    {
        return [
            'productId' => $this->productId->value(),
            'name' => $this->name->value(),
            'price' => $this->price->value(),
            'quantity' => $this->quantity->value(),
            'totalPrice' => $this->totalPrice()->value()
        ];
    }
}