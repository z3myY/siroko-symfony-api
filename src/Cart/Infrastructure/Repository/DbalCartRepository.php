<?php

declare(strict_types=1);

namespace App\Cart\Infrastructure\Repository;

use App\Cart\Domain\Repository\CartRepositoryInterface;
use App\Cart\Domain\Entity\Cart;
use App\Cart\Domain\Entity\CartProduct;
use App\Shared\Domain\ValueObject\FloatValueObject;
use App\Shared\Domain\ValueObject\IntValueObject;
use App\Shared\Domain\ValueObject\StringValueObject;
use Doctrine\DBAL\Connection;

/**
 * Class DbalCartRepository
 * @package App\Cart\Infrastructure\Repository
 */
class DbalCartRepository implements CartRepositoryInterface
{
    public function __construct(private Connection $connection) {}

    public function create(Cart $cart): int
    {
        return $this->connection->createQueryBuilder()
            ->insert('carts')
            ->values(['user_id' => ':user_id'])
            ->setParameter('user_id', $cart->userId()->value())
            ->executeStatement();
    }

    public function findAll(): array
    {
        $cartsData = $this->connection->createQueryBuilder()
            ->select('*')
            ->from('carts')
            ->executeQuery()
            ->fetchAllAssociative();

        return array_map([$this, 'hydrateResult'], $cartsData);
    }

    public function findById(IntValueObject $id): ?Cart
    {
        $cartData = $this->connection->createQueryBuilder()
            ->select('*')
            ->from('carts')
            ->where('id = :id')
            ->setParameter('id', $id->value())
            ->executeQuery()
            ->fetchAssociative();

        return $cartData ? $this->hydrateResult($cartData) : null;
    }

    public function findByUserId(IntValueObject $userId): ?Cart
    {
        $cartData = $this->connection->createQueryBuilder()
            ->select('*')
            ->from('carts')
            ->where('user_id = :user_id')
            ->setParameter('user_id', $userId->value())
            ->executeQuery()
            ->fetchAssociative();

        return $cartData ? $this->hydrateResult($cartData) : null;
    }

    public function save(Cart $cart): void
    {
        $this->connection->createQueryBuilder()
            ->update('carts')
            ->set('user_id', ':user_id')
            ->where('id = :id')
            ->setParameter('id', $cart->id()->value())
            ->setParameter('user_id', $cart->userId()->value())
            ->executeStatement();

        foreach ($cart->products() as $product) {
            $this->saveProduct($product);
        }
    }

    public function saveProduct(CartProduct $cartProduct): void
    {
        if ($this->isProductInCart($cartProduct)) {
            $this->connection->createQueryBuilder()
                ->update('cart_products')
                ->set('quantity', ':quantity')
                ->set('name', ':name')
                ->set('price', ':price')
                ->where('cart_id = :cart_id')
                ->andWhere('product_id = :product_id')
                ->setParameter('quantity', $cartProduct->quantity()->value())
                ->setParameter('cart_id', $cartProduct->cartId()->value())
                ->setParameter('product_id', $cartProduct->productId()->value())
                ->setParameter('name', $cartProduct->name()->value())
                ->setParameter('price', $cartProduct->price()->value())
                ->executeStatement();
        } else {
            $this->connection->createQueryBuilder()
                ->insert('cart_products')
                ->values([
                    'cart_id' => ':cart_id',
                    'product_id' => ':product_id',
                    'quantity' => ':quantity',
                    'name' => ':name',
                    'price' => ':price'
                ])
                ->setParameter('cart_id', $cartProduct->cartId()->value())
                ->setParameter('product_id', $cartProduct->productId()->value())
                ->setParameter('quantity', $cartProduct->quantity()->value())
                ->setParameter('name', $cartProduct->name()->value())
                ->setParameter('price', $cartProduct->price()->value())
                ->executeStatement();
        }
    }

    private function isProductInCart(CartProduct $cartProduct): bool
    {
        $product = $this->connection->createQueryBuilder()
            ->select('id')
            ->from('cart_products')
            ->where('cart_id = :cart_id')
            ->andWhere('product_id = :product_id')
            ->setParameter('cart_id', $cartProduct->cartId()->value())
            ->setParameter('product_id', $cartProduct->productId()->value())
            ->executeQuery()
            ->fetchAssociative();

        return $product !== false;
        
    }

    public function remove(IntValueObject $id): void
    {
        $this->connection->createQueryBuilder()
            ->delete('carts')
            ->where('id = :id')
            ->setParameter('id', $id->value())
            ->executeStatement();
    }

    public function removeProduct(CartProduct $cartProduct): void
    {
        $this->connection->createQueryBuilder()
            ->delete('cart_products')
            ->where('id = :id')
            ->setParameter('id', $cartProduct->productId()->value())
            ->executeStatement();
    }

    public function clear(Cart $cart): void
    {
        $this->connection->createQueryBuilder()
            ->delete('cart_products')
            ->where('cart_id = :cart_id')
            ->setParameter('cart_id', $cart->id()->value())
            ->executeStatement();
    }

    public function lastCart(): ?Cart
    {
        $cartData = $this->connection->createQueryBuilder()
            ->select('*')
            ->from('carts')
            ->orderBy('id', 'DESC')
            ->setMaxResults(1)
            ->executeQuery()
            ->fetchAssociative();

        return $cartData ? $this->hydrateResult($cartData) : null;
    }

    private function hydrateResult(array $data): Cart
    {
        $productsData = $this->connection->createQueryBuilder()
            ->select('*')
            ->from('cart_products')
            ->where('cart_id = :cart_id')
            ->setParameter('cart_id', $data['id'])
            ->executeQuery()
            ->fetchAllAssociative();

        $products = array_map(fn($productData) => new CartProduct(
            IntValueObject::fromInt((int) $productData['cart_id']),
            IntValueObject::fromInt((int) $productData['product_id']),
            IntValueObject::fromInt((int) $productData['quantity']),
            StringValueObject::fromString($productData['name']),
            FloatValueObject::from((float) $productData['price'])
        ), $productsData);

        return Cart::load(
            IntValueObject::fromInt((int) $data['id']),
            IntValueObject::fromInt((int) $data['user_id']),
            $products
        );
    }
}
