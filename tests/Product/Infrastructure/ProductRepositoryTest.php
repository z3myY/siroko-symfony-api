<?php

declare(strict_types=1);

namespace Tests\Product\Infrastructure;

use App\Product\Domain\Entity\Product;
use App\Product\Infrastructure\Repository\ProductRepository;
use App\Shared\Domain\ValueObject\FloatValueObject;
use App\Shared\Domain\ValueObject\IntValueObject;
use App\Shared\Domain\ValueObject\StringValueObject;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Result;
use PHPUnit\Framework\TestCase;

class ProductRepositoryTest extends TestCase
{
    private ProductRepository $repository;
    private Connection $connection;

    protected function setUp(): void
    {
        $this->connection = $this->createMock(Connection::class);
        $this->repository = new ProductRepository($this->connection);
    }

    public function testFindByIdReturnsNullWhenNotFound(): void
    {
        $productId = IntValueObject::fromInt(1);

        $result = $this->createStub(Result::class);
        $result->method('fetchAssociative')->willReturn(false);

        $queryBuilder = $this->createMock(QueryBuilder::class);
        $queryBuilder->method('select')->willReturnSelf();
        $queryBuilder->method('from')->willReturnSelf();
        $queryBuilder->method('where')->willReturnSelf();
        $queryBuilder->method('setParameter')->willReturnSelf();
        $queryBuilder->method('executeQuery')->willReturn($result);

        $this->connection->method('createQueryBuilder')->willReturn($queryBuilder);

        $product = $this->repository->findById($productId);

        $this->assertNull($product);
    }

    public function testListReturnsEmptyArrayWhenNoProducts(): void
    {
        $statement = $this->createMock(Result::class);
        $statement->method('fetchAllAssociative')->willReturn([]);

        $queryBuilder = $this->createQueryBuilderMock();
        $queryBuilder->method('executeQuery')->willReturn($statement);

        $this->connection->method('createQueryBuilder')->willReturn($queryBuilder);

        $products = $this->repository->list();

        $this->assertEmpty($products);
    }

    public function testUpdateStock(): void
    {
        $product = $this->createProduct();

        $this->connection
            ->expects($this->once())
            ->method('createQueryBuilder')
            ->willReturn($this->createQueryBuilderMock());

        $this->repository->updateStock($product);

        $this->assertTrue(true); // If no exception is thrown, the test passes
    }

    private function createProduct(): Product
    {
        return Product::load(
            IntValueObject::fromInt(1),
            StringValueObject::fromString('Product Name'),
            StringValueObject::fromString('Product Description'),
            FloatValueObject::from(100.0),
            IntValueObject::fromInt(10),
            StringValueObject::fromString('http://example.com/image.jpg'),
            StringValueObject::fromString('Category'),
            StringValueObject::fromString('SKU123'),
            StringValueObject::fromString('In Stock'),
            FloatValueObject::from(10.0),
            StringValueObject::fromString('Brand'),
            FloatValueObject::from(4.5),
            IntValueObject::fromInt(100)
        );
    }

    private function createQueryBuilderMock(): QueryBuilder
    {
        $queryBuilder = $this->createMock(QueryBuilder::class);
        $queryBuilder->method('select')->willReturnSelf();
        $queryBuilder->method('from')->willReturnSelf();
        $queryBuilder->method('where')->willReturnSelf();
        $queryBuilder->method('setParameter')->willReturnSelf();
        $queryBuilder->method('executeQuery')->willReturn($this->createMock(Result::class));
        $queryBuilder->method('fetchAssociative')->willReturn([]);
        $queryBuilder->method('fetchAllAssociative')->willReturn([]);
        $queryBuilder->method('update')->willReturnSelf();
        $queryBuilder->method('set')->willReturnSelf();
        $queryBuilder->method('executeStatement')->willReturn(1);

        return $queryBuilder;
    }
}
