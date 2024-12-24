<?php

declare(strict_types=1);

namespace Tests\Product\Domain\Service;

use App\Product\Domain\Service\ProductStockService;
use App\Product\Domain\Repository\ProductRepositoryInterface;
use App\Product\Domain\Entity\Product;
use App\Shared\Domain\ValueObject\IntValueObject;
use PHPUnit\Framework\TestCase;

class ProductStockServiceTest extends TestCase
{
    private ProductRepositoryInterface $productRepository;
    private $productStockService;

    protected function setUp(): void
    {
        $this->productRepository = $this->createMock(ProductRepositoryInterface::class);
        $this->productStockService = new ProductStockService($this->productRepository);
    }

    public function testCheckStockReturnsTrueWhenStockIsSufficient(): void
    {
        $productId = IntValueObject::fromInt(1);
        $quantity = IntValueObject::fromInt(5);
        $product = $this->createMock(Product::class);
        $product->method('stock')->willReturn(IntValueObject::fromInt(10));

        $this->productRepository
            ->expects($this->once())
            ->method('findById')
            ->with($productId)
            ->willReturn($product);

        $result = $this->productStockService->checkStock($productId, $quantity);

        $this->assertTrue($result);
    }

    public function testCheckStockReturnsFalseWhenStockIsInsufficient(): void
    {
        $productId = IntValueObject::fromInt(1);
        $quantity = IntValueObject::fromInt(15);
        $product = $this->createMock(Product::class);
        $product->method('stock')->willReturn(IntValueObject::fromInt(10));

        $this->productRepository
            ->expects($this->once())
            ->method('findById')
            ->with($productId)
            ->willReturn($product);

        $result = $this->productStockService->checkStock($productId, $quantity);

        $this->assertFalse($result);
    }

    public function testCheckStockThrowsExceptionWhenProductNotFound(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Product not found');

        $productId = IntValueObject::fromInt(1);
        $quantity = IntValueObject::fromInt(5);

        $this->productRepository
            ->expects($this->once())
            ->method('findById')
            ->with($productId)
            ->willReturn(null);

        $this->productStockService->checkStock($productId, $quantity);
    }
}