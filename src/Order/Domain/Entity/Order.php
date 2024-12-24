<?php

declare(strict_types=1);

namespace App\Order\Domain\Entity;

use App\Shared\Domain\AggregateRoot;
use App\Shared\Domain\ValueObject\FloatValueObject;
use App\Shared\Domain\ValueObject\IntValueObject;

class Order extends AggregateRoot
{
    public function __construct(
        private IntValueObject $userId,
        /** @var array<OrderProducts> */
        private array $products,
        private ?IntValueObject $id = null
    ) {
    }

    public function userId(): IntValueObject
    {
        return $this->userId;
    }

    public function id(): ?IntValueObject
    {
        return $this->id;
    }

    /**
     * Undocumented function
     *
     * @return array<OrderProducts>
     */
    public function products(): array
    {
        return $this->products;
    }

    public function totalPrice(): FloatValueObject
    {
        return FloatValueObject::from((array_sum(array_map(fn(array $product) => $product['price'] * $product['quantity'], $this->products))));
    }

    public static function load(
        IntValueObject $userId,
        array $products,
        ?IntValueObject $id = null,
    ): self {
        return new self(
            userId: $userId,
            products: $products,
            id: $id
        );
    }

    public function serialize(): array
    {
        return [
            'id' => $this->id()->value(),
            'userId' => $this->userId->value(),
            'products' => array_map(fn(OrderProducts $product) => $product->serialize(), $this->products),
            'totalPrice' => $this->totalPrice()->value()
        ];
    }
    
}