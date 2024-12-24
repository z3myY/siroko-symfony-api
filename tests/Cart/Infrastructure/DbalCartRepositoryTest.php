<?php

declare(strict_types=1);

namespace App\Tests\Cart\Infrastructure\Repository;

use App\Cart\Domain\Entity\Cart;
use App\Cart\Infrastructure\Repository\DbalCartRepository;
use App\Shared\Domain\ValueObject\IntValueObject;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Result;
use Doctrine\DBAL\Query\QueryBuilder;
use PHPUnit\Framework\TestCase;

class DbalCartRepositoryTest extends TestCase
{
    private Connection $connection;
    private DbalCartRepository $repository;

    protected function setUp(): void
    {
        $this->connection = $this->createMock(Connection::class);
        $this->repository = new DbalCartRepository($this->connection);
    }

    public function testCreate(): void
    {
        $cart = $this->createMock(Cart::class);
        $cart->method('userId')->willReturn(IntValueObject::fromInt(1));

        $queryBuilder = $this->createMock(QueryBuilder::class);
        $queryBuilder->method('insert')->willReturnSelf();
        $queryBuilder->method('values')->willReturnSelf();
        $queryBuilder->method('setParameter')->willReturnSelf();
        $queryBuilder->method('executeStatement')->willReturn(1);

        $this->connection->method('createQueryBuilder')->willReturn($queryBuilder);
        $this->connection->method('lastInsertId')->willReturn('1');

        $result = $this->repository->create($cart);

        $this->assertEquals(1, $result);
    }

    public function testFindById(): void
    {
        $id = IntValueObject::fromInt(1);
        $cartData = [
            'id' => 1,
            'user_id' => 1,
        ];

        $result = $this->createStub(Result::class);
        $result->method('fetchAssociative')->willReturn($cartData);

        $queryBuilder = $this->createMock(QueryBuilder::class);
        $queryBuilder->method('select')->willReturnSelf();
        $queryBuilder->method('from')->willReturnSelf();
        $queryBuilder->method('where')->willReturnSelf();
        $queryBuilder->method('setParameter')->willReturnSelf();
        $queryBuilder->method('executeQuery')->willReturn($result);

        $this->connection->method('createQueryBuilder')->willReturn($queryBuilder);

        $result = $this->repository->findById($id);

        $this->assertInstanceOf(Cart::class, $result);
        $this->assertEquals($cartData['id'], $result->id()->value());
        $this->assertEquals($cartData['user_id'], $result->userId()->value());
    }

    public function testFindByIdReturnsNullWhenNotFound(): void
    {
        $id = IntValueObject::fromInt(1);

        $result = $this->createStub(Result::class);
        $result->method('fetchAssociative')->willReturn(false);

        $queryBuilder = $this->createMock(QueryBuilder::class);
        $queryBuilder->method('select')->willReturnSelf();
        $queryBuilder->method('from')->willReturnSelf();
        $queryBuilder->method('where')->willReturnSelf();
        $queryBuilder->method('setParameter')->willReturnSelf();
        $queryBuilder->method('executeQuery')->willReturn($result);

        $this->connection->method('createQueryBuilder')->willReturn($queryBuilder);

        $result = $this->repository->findById($id);

        $this->assertNull($result);
    }

}