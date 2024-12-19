<?php

declare(strict_types=1);

namespace App\Cart\Domain\Entity;

use App\Shared\Domain\AggregateRoot;
use App\Cart\Domain\Entity\CartProduct;
use App\Shared\Domain\ValueObject\IntValueObject;

/**
 * Class Cart
 * @package App\Cart\Domain\Entity
 */
final class Cart extends AggregateRoot
{
    public function __construct(
        private ?IntValueObject $id,
        private IntValueObject $userId,
        /** @var CartProduct[] */
        private array $products = []
    ) {
    }

    public function id(): ?IntValueObject
    {
        return $this->id;
    }

    public function userId(): IntValueObject
    {
        return $this->userId;
    }

    public function products(): array
    {
        return $this->products;
    }

    public function addProduct(CartProduct $cartProduct): void
    {
        foreach ($this->products as $existingProduct) {
            if ($existingProduct->productId()->equalsTo($cartProduct->productId())) {
                $existingProduct->increaseQuantity($cartProduct->quantity());
                return;
            }
        }
        $this->products[] = $cartProduct;
    }

    public function removeProduct(IntValueObject $productId): void
    {
        $this->products = array_values(array_filter($this->products, fn(?CartProduct $product) => $product !== null && !$product->productId()->equalsTo($productId)));
    }

    public function clearProducts(): void
    {
        $this->products = [];
    }

    public function totalProducts(): IntValueObject
    {
        return IntValueObject::fromInt(count($this->products));
    }

    public function getProduct(IntValueObject $productId): ?CartProduct
    {
        foreach ($this->products as $product) {
            if ($product->productId()->equalsTo($productId)) {
                return $product;
            }
        }
        return null;
    }

    public static function load(
        ?IntValueObject $id,
        IntValueObject $userId,
        array $products = []
    ) : self {
        return new self(
            id: $id, 
            userId: $userId, 
            products: $products
        );
    }

    public function serialize(): array
    {
        return [
            'id' => $this->id?->value(),
            'userId' => $this->userId->value(),
            'products' => array_map(fn(CartProduct $product) => $product->serialize(), $this->products)
        ];
    }
}