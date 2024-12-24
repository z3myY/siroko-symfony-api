<?php

declare(strict_types=1);

namespace App\Order\Infrastructure\Repository;

use App\Order\Domain\Entity\OrderProducts;
use App\Shared\Domain\ValueObject\FloatValueObject;
use App\Shared\Domain\ValueObject\IntValueObject;
use App\Shared\Domain\ValueObject\StringValueObject;
use Doctrine\DBAL\Connection;
use PHPUnit\Framework\TestCase;

class DbalOrderProductsRepositoryTest extends TestCase
{
    private Connection $connection;
    private $repository;

    protected function setUp(): void
    {
        $this->connection = $this->createMock(Connection::class);
        $this->repository = new DbalOrderProductsRepository($this->connection);
    }

    public function testSaveInsertsOrderProducts(): void
    {
        $orderProducts = new OrderProducts(
            IntValueObject::fromInt(1),
            IntValueObject::fromInt(2),
            StringValueObject::fromString('Product Name'),
            FloatValueObject::from(10.0),
            IntValueObject::fromInt(3)
        );

        $this->connection
            ->expects($this->once())
            ->method('insert')
            ->with('order_products', [
                'order_id' => 1,
                'product_id' => 2,
                'name' => 'Product Name',
                'price' => 10.0,
                'quantity' => 3
            ]);

        $this->repository->save($orderProducts);
    }

    public function testSaveThrowsExceptionOnFailure(): void
    {
        $orderProducts = new OrderProducts(
            IntValueObject::fromInt(1),
            IntValueObject::fromInt(2),
            StringValueObject::fromString('Product Name'),
            FloatValueObject::from(10.0),
            IntValueObject::fromInt(3)
        );

        $this->connection
            ->expects($this->once())
            ->method('insert')
            ->will($this->throwException(new \Exception('DB error')));

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('DB error');

        $this->repository->save($orderProducts);
    }
}