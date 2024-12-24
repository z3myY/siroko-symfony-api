<?php

namespace Tests\Product\Domain\Service;

use App\Product\Domain\Service\IncrementProductStockService;
use App\Product\Domain\Repository\ProductRepositoryInterface;
use App\Product\Domain\Entity\Product;
use App\Shared\Domain\ValueObject\IntValueObject;
use PHPUnit\Framework\TestCase;

class IncrementProductStockServiceTest extends TestCase
{
    private ProductRepositoryInterface $productRepository;
    private $incrementProductStockService;

    protected function setUp(): void
    {
        $this->productRepository = $this->createMock(ProductRepositoryInterface::class);
        $this->incrementProductStockService = new IncrementProductStockService($this->productRepository);
    }

    public function testExecuteThrowsExceptionWhenProductNotFound(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Product not found');

        $productId = IntValueObject::fromInt(1);
        $quantity = IntValueObject::fromInt(10);

        $this->productRepository
            ->expects($this->once())
            ->method('findById')
            ->with($productId)
            ->willReturn(null);

        $this->incrementProductStockService->execute($productId, $quantity);
    }

    public function testExecuteIncrementsProductStock(): void
    {
        $productId = IntValueObject::fromInt(1);
        $quantity = IntValueObject::fromInt(10);
        $newStock = IntValueObject::fromInt(30);

        $product = $this->createMock(Product::class);

        $product
            ->expects($this->exactly(2))
            ->method('stock');

        $this->productRepository
            ->expects($this->once())
            ->method('findById')
            ->with($productId)
            ->willReturn($product);

        $this->productRepository
            ->expects($this->once())
            ->method('updateStock')
            ->with($product);

        $this->incrementProductStockService->execute($productId, $quantity);
    }
}