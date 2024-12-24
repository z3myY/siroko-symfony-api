<?php

declare(strict_types=1);

namespace Tests\Product\Domain\Service;

use App\Product\Domain\Service\DecrementProductStockService;
use App\Product\Domain\Repository\ProductRepositoryInterface;
use App\Product\Domain\Entity\Product;
use App\Shared\Domain\ValueObject\IntValueObject;
use PHPUnit\Framework\TestCase;

class DecrementProductStockServiceTest extends TestCase
{
    private ProductRepositoryInterface $productRepository;
    private $decrementProductStockService;

    protected function setUp(): void
    {
        $this->productRepository = $this->createMock(ProductRepositoryInterface::class);
        $this->decrementProductStockService = new DecrementProductStockService($this->productRepository);
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

        $this->decrementProductStockService->execute($productId, $quantity);
    }

    public function testExecuteDecrementsProductStock(): void
    {
        $productId = IntValueObject::fromInt(1);
        $quantity = IntValueObject::fromInt(10);
        $initialStock = IntValueObject::fromInt(20);
        $newStock = IntValueObject::fromInt(10);

        $product = $this->createMock(Product::class);

        $product
            ->expects($this->once())
            ->method('stock')
            ->willReturn($initialStock);

        $this->productRepository
            ->expects($this->once())
            ->method('findById')
            ->with($productId)
            ->willReturn($product);

        $this->productRepository
            ->expects($this->once())
            ->method('updateStock')
            ->with($product);

        $this->decrementProductStockService->execute($productId, $quantity);
    }
}