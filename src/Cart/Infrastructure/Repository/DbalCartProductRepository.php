<?php

declare(strict_types=1);

namespace App\Cart\Infrastructure\Repository;

use App\Cart\Domain\Entity\CartProduct;
use App\Cart\Domain\Repository\CartProductRepositoryInterface;
use App\Shared\Domain\ValueObject\FloatValueObject;
use App\Shared\Domain\ValueObject\IntValueObject;
use App\Shared\Domain\ValueObject\StringValueObject;
use Doctrine\DBAL\Connection;

final class DbalCartProductRepository implements CartProductRepositoryInterface
{
    public function __construct(private Connection $connection)
    {
    }

    public function findById(IntValueObject $id): ?CartProduct
    {
        $cartProductData = $this->connection->createQueryBuilder()
            ->select('*')
            ->from('cart_products')
            ->where('id = :id')
            ->setParameter('id', $id->value())
            ->executeQuery()
            ->fetchAssociative();

        return $cartProductData ? $this->hydrateResult($cartProductData) : null;
    }

    public function find(CartProduct $cartProduct): ?CartProduct
    {
        $cartProductData = $this->connection->createQueryBuilder()
            ->select('*')
            ->from('cart_products')
            ->where('cart_id = :cart_id')
            ->andWhere('product_id = :product_id')
            ->setParameter('cart_id', $cartProduct->cartId()->value())
            ->setParameter('product_id', $cartProduct->productId()->value())
            ->executeQuery()
            ->fetchAssociative();

        if ($cartProductData) {
            return $this->hydrateResult($cartProductData);
        }
    }

    public function removeProduct(CartProduct $cartProduct): void
    {
        $this->connection->createQueryBuilder()
            ->delete('cart_products')
            ->where('cart_id = :cart_id')
            ->andWhere('product_id = :product_id')
            ->setParameter('cart_id', $cartProduct->cartId()->value())
            ->setParameter('product_id', $cartProduct->productId()->value())
            ->executeStatement();
    }

    private function hydrateResult(array $cartProductData): CartProduct
    {
        return CartProduct::load(
            IntValueObject::fromInt((int) $cartProductData['cart_id']),
            IntValueObject::fromInt((int) $cartProductData['product_id']),
            IntValueObject::fromInt((int) $cartProductData['quantity']),
            StringValueObject::fromString((string) $cartProductData['name']),
            FloatValueObject::from((float) $cartProductData['price'])
        );
    }
}