<?php

declare(strict_types=1);

namespace Tests\Product\Application;

use App\Product\Application\Read\ListProducts;
use App\Product\Domain\Entity\Product;
use App\Product\Domain\Repository\ProductRepositoryInterface;
use PHPUnit\Framework\TestCase;

class ListProductsTest extends TestCase
{
    private ProductRepositoryInterface $productRepository;
    private ListProducts $listProducts;

    protected function setUp(): void
    {
        $this->productRepository = $this->createMock(ProductRepositoryInterface::class);
        $this->listProducts = new ListProducts($this->productRepository);
    }

    public function testExecuteReturnsListOfProducts(): void
    {
        $product1 = $this->createMock(Product::class);
        $product2 = $this->createMock(Product::class);
        $products = [$product1, $product2];

        $this->productRepository
            ->expects($this->once())
            ->method('list')
            ->willReturn($products);

        $result = $this->listProducts->execute();

        $this->assertSame($products, $result);
    }

    public function testExecuteReturnsEmptyArrayWhenNoProducts(): void
    {
        $this->productRepository
            ->expects($this->once())
            ->method('list')
            ->willReturn([]);

        $result = $this->listProducts->execute();

        $this->assertEmpty($result);
    }
}