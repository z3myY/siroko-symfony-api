<?php

declare(strict_types=1);

namespace App\Tests\Cart\Infrastructure\Repository;

use App\Cart\Domain\Entity\CartProduct;
use App\Cart\Infrastructure\Repository\DbalCartProductRepository;
use App\Shared\Domain\ValueObject\IntValueObject;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Result;
use Doctrine\DBAL\Query\QueryBuilder;
use PHPUnit\Framework\TestCase;

class DbalCartProductRepositoryTest extends TestCase
{
    private Connection $connection;
    private DbalCartProductRepository $repository;

    protected function setUp(): void
    {
        $this->connection = $this->createMock(Connection::class);
        $this->repository = new DbalCartProductRepository($this->connection);
    }

    public function testFindByIdReturnsCartProduct(): void
    {
        $cartProductData = [
            'cart_id' => 1,
            'product_id' => 2,
            'quantity' => 3,
            'name' => 'Product Name',
            'price' => 10.0,
        ];

        $statement = $this->createStub(Result::class);
        $statement->method('fetchAssociative')->willReturn($cartProductData);

        $queryBuilder = $this->createMock(QueryBuilder::class);
        $queryBuilder->method('select')->willReturnSelf();
        $queryBuilder->method('from')->willReturnSelf();
        $queryBuilder->method('where')->willReturnSelf();
        $queryBuilder->method('setParameter')->willReturnSelf();
        $queryBuilder->method('executeQuery')->willReturn($statement);

        $this->connection->method('createQueryBuilder')->willReturn($queryBuilder);

        $cartProductId = IntValueObject::fromInt(1);
        $result = $this->repository->findById($cartProductId);

        $this->assertInstanceOf(CartProduct::class, $result);
        $this->assertEquals($cartProductData['cart_id'], $result->cartId()->value());
        $this->assertEquals($cartProductData['product_id'], $result->productId()->value());
        $this->assertEquals($cartProductData['quantity'], $result->quantity()->value());
        $this->assertEquals($cartProductData['name'], $result->name()->value());
        $this->assertEquals($cartProductData['price'], $result->price()->value());
    }
}