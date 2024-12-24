<?php

declare(strict_types=1);

namespace Tests\Product\Application\Read;

use App\Product\Application\Read\FindProduct;
use App\Product\Domain\Entity\Product;
use App\Product\Domain\Repository\ProductRepositoryInterface;
use App\Shared\Domain\ValueObject\IntValueObject;
use PHPUnit\Framework\TestCase;

class FindProductTest extends TestCase
{
    private ProductRepositoryInterface $productRepository;
    private FindProduct $findProduct;

    protected function setUp(): void
    {
        $this->productRepository = $this->createMock(ProductRepositoryInterface::class);
        $this->findProduct = new FindProduct($this->productRepository);
    }

    public function testExecuteReturnsSerializedProduct(): void
    {
        $productId = IntValueObject::fromInt(1);
        $product = $this->createMock(Product::class);
        $serializedProduct = ['id' => 1, 'name' => 'Product Name', 'price' => 100.0, 'stock' => 10];

        $product
            ->expects($this->once())
            ->method('serialize')
            ->willReturn($serializedProduct);

        $this->productRepository
            ->expects($this->once())
            ->method('findById')
            ->with($productId)
            ->willReturn($product);

        $result = $this->findProduct->execute(1);

        $this->assertSame($serializedProduct, $result);
    }

    public function testExecuteThrowsExceptionWhenProductNotFound(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Product not found');

        $productId = IntValueObject::fromInt(1);

        $this->productRepository
            ->expects($this->once())
            ->method('findById')
            ->with($productId)
            ->willReturn(null);

        $this->findProduct->execute(1);
    }
}