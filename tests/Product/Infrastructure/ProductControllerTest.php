<?php

declare(strict_types=1);

namespace Tests\Product\Infrastructure\Controller;

use App\Product\Application\Read\FindProduct;
use App\Product\Application\Read\ListProducts;
use App\Product\Infrastructure\Controller\ProductController;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ProductControllerTest extends TestCase
{
    private FindProduct $findProduct;
    private ListProducts $listProducts;
    private $productController;

    protected function setUp(): void
    {
        $this->findProduct = $this->createMock(FindProduct::class);
        $this->listProducts = $this->createMock(ListProducts::class);
        $this->productController = new ProductController($this->findProduct, $this->listProducts);
    }

    public function testListProductReturnsListOfProducts(): void
    {
        $products = [
            ['id' => 1, 'name' => 'Product 1', 'price' => 100.0],
            ['id' => 2, 'name' => 'Product 2', 'price' => 200.0]
        ];

        $this->listProducts
            ->expects($this->once())
            ->method('execute')
            ->willReturn($products);

        $response = $this->productController->listProduct();

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($products), $response->getContent());
    }

    public function testListProductReturnsEmptyArrayWhenNoProducts(): void
    {
        $this->listProducts
            ->expects($this->once())
            ->method('execute')
            ->willReturn([]);

        $response = $this->productController->listProduct();

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode([]), $response->getContent());
    }

    public function testFindProductReturnsProduct(): void
    {
        $product = ['id' => 1, 'name' => 'Product 1', 'price' => 100.0];

        $this->findProduct
            ->expects($this->once())
            ->method('execute')
            ->with(1)
            ->willReturn($product);

        $response = $this->productController->findProduct(1);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($product), $response->getContent());
    }

    public function testFindProductReturnsNotFoundWhenProductDoesNotExist(): void
    {
        $this->findProduct
            ->expects($this->once())
            ->method('execute')
            ->with(1)
            ->willReturn([]);

        $response = $this->productController->findProduct(1);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
        $this->assertEquals(json_encode(['error' => 'Product not found']), $response->getContent());
    }
}