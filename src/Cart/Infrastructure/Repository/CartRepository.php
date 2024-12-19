<?php

declare(strict_types=1);

namespace App\Cart\Infrastructure\Repository;

use App\Cart\Domain\Repository\CartRepositoryInterface;
use App\Cart\Domain\Entity\Cart;
use App\Cart\Domain\Entity\CartProduct;
use App\Shared\Domain\ValueObject\IntValueObject;
use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class CartRepository
 * @package App\Cart\Infrastructure\Repository
 */
class CartRepository implements CartRepositoryInterface
{
    public function __construct(private Connection $connection)
    {
    }

    public function create(IntValueObject $userId): int
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder
            ->insert('carts')
            ->values([
                'user_id' => ':user_id',
            ])
            ->setParameter('user_id', $userId->value());
        
        return $queryBuilder->executeStatement();
    }

    public function findById(IntValueObject $id): ?Cart
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder
            ->select('*')
            ->from('carts')
            ->where('id = :id')
            ->setParameter('id', $id->value());

        $cartData = $queryBuilder->executeQuery()->fetchAssociative();

        if (!$cartData) {
            return null;
        }

        return $this->hydrateResult($cartData);
    }

    public function save(CartProduct $cartProduct): void
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder
            ->insert('cart_products')
            ->values([
                'cart_id' => ':cart_id',
                'product_id' => ':product_id',
                'quantity' => ':quantity',
            ])
            ->setParameter('cart_id', $cartProduct->cartId()->value())
            ->setParameter('product_id', $cartProduct->productId()->value())
            ->setParameter('quantity', $cartProduct->quantity()->value());

        $queryBuilder->executeStatement();
    }

    public function remove(IntValueObject $id): void
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder
            ->delete('carts')
            ->where('id = :id')
            ->setParameter('id', $id->value());

        $queryBuilder->executeStatement();
    }

    private function hydrateResult(array $data): Cart
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder
            ->select('*')
            ->from('cart_products')
            ->where('cart_id = :cart_id')
            ->setParameter('cart_id', $data['id']);

        $productsData = $queryBuilder->executeQuery()->fetchAllAssociative();

        $products = array_map(function ($productData) {
            return new CartProduct(
                IntValueObject::fromInt((int) $productData['cart_id']),
                IntValueObject::fromInt((int) $productData['product_id']),
                IntValueObject::fromInt((int) $productData['quantity'])
            );
        }, $productsData);

        return Cart::load(
            IntValueObject::fromInt((int) $data['id']),
            IntValueObject::fromInt((int) $data['user_id']),
            $products
        );
    }
}