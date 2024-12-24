<?php

declare(strict_types=1);

namespace App\Order\Infrastructure\Repository;

use App\Order\Domain\Entity\Order;
use App\Order\Infrastructure\Repository\DbalOrderRepository;
use App\Shared\Domain\ValueObject\FloatValueObject;
use App\Shared\Domain\ValueObject\IntValueObject;
use Doctrine\DBAL\Connection;
use PHPUnit\Framework\TestCase;

class DbalOrderRepositoryTest extends TestCase
{
    private Connection $connection;
    private $repository;

    protected function setUp(): void
    {
        $this->connection = $this->createMock(Connection::class);
        $this->repository = new DbalOrderRepository($this->connection);
    }

    public function testSaveInsertsOrder(): void
    {
        $order = new Order(
            IntValueObject::fromInt(1),
            [
                [
                    'productId' => 1,
                    'name' => 'Product 1',
                    'price' => 50.0,
                    'quantity' => 2
                ]
            ]
        );

        $queryBuilder = $this->createMock(\Doctrine\DBAL\Query\QueryBuilder::class);
        $queryBuilder->expects($this->once())
            ->method('insert')
            ->with('orders')
            ->willReturn($queryBuilder);
        $queryBuilder->expects($this->exactly(2))
            ->method('setValue')
            ->with($this->logicalOr(
                $this->equalTo('user_id'),
                $this->equalTo('total_price')
            ), $this->logicalOr(
                $this->equalTo(':user_id'),
                $this->equalTo(':total_price')
            ))
            ->willReturnOnConsecutiveCalls($queryBuilder, $queryBuilder);
        $queryBuilder->expects($this->exactly(2))
            ->method('setParameter')
            ->with($this->logicalOr(
                $this->equalTo('user_id'),
                $this->equalTo('total_price')
            ), $this->logicalOr(
                $this->equalTo(1),
                $this->equalTo(100.0)
            ))
            ->willReturnOnConsecutiveCalls($queryBuilder, $queryBuilder);
        $queryBuilder->expects($this->once())
            ->method('executeStatement');

        $this->connection->expects($this->once())
            ->method('createQueryBuilder')
            ->willReturn($queryBuilder);

        $this->connection->expects($this->once())
            ->method('lastInsertId')
            ->willReturn(1);

        $result = $this->repository->save($order);

        $this->assertEquals(1, $result);
    }

    public function testSaveThrowsExceptionOnFailure(): void
    {
        $order = new Order(
            IntValueObject::fromInt(1),
            [
                [
                    'productId' => 1,
                    'name' => 'Product 1',
                    'price' => 50.0,
                    'quantity' => 2
                ]
            ]
        );

        $queryBuilder = $this->createMock(\Doctrine\DBAL\Query\QueryBuilder::class);
        $queryBuilder->expects($this->once())
            ->method('insert')
            ->with('orders')
            ->willReturn($queryBuilder);
        $queryBuilder->expects($this->exactly(2))
            ->method('setValue')
            ->with($this->logicalOr(
                $this->equalTo('user_id'),
                $this->equalTo('total_price')
            ), $this->logicalOr(
                $this->equalTo(':user_id'),
                $this->equalTo(':total_price')
            ))
            ->willReturnOnConsecutiveCalls($queryBuilder, $queryBuilder);
        $queryBuilder->expects($this->exactly(2))
            ->method('setParameter')
            ->with($this->logicalOr(
                $this->equalTo('user_id'),
                $this->equalTo('total_price')
            ), $this->logicalOr(
                $this->equalTo(1),
                $this->equalTo(100.0)
            ))
            ->willReturnOnConsecutiveCalls($queryBuilder, $queryBuilder);

        // Simulate an exception being thrown
        $queryBuilder->expects($this->once())
            ->method('executeStatement')
            ->willThrowException(new \Exception('Database error'));

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Database error');

        $this->connection->expects($this->once())
            ->method('createQueryBuilder')
            ->willReturn($queryBuilder);

        $repository = new DbalOrderRepository($this->connection);
        $repository->save($order);
    }
}
