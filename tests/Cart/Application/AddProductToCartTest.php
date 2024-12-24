<?php

declare(strict_types=1);

namespace App\Tests\Cart\Application;

use App\Cart\Application\Update\AddProductToCart;
use App\Cart\Domain\Entity\Cart;
use App\Cart\Domain\Entity\CartProduct;
use App\Cart\Domain\Repository\CartRepositoryInterface;
use App\Product\Domain\Service\DecrementProductStockService;
use App\Product\Domain\Service\ProductStockService;
use App\Shared\Domain\ValueObject\IntValueObject;
use App\Shared\Domain\ValueObject\StringValueObject;
use App\Shared\Domain\ValueObject\FloatValueObject;
use PHPUnit\Framework\TestCase;

class AddProductToCartTest extends TestCase
{
    private CartRepositoryInterface $cartRepository;
    private ProductStockService $productStockService;
    private DecrementProductStockService $decrementProductStockService;
    private $addProductToCart;

    protected function setUp(): void
    {
        $this->cartRepository = $this->createMock(CartRepositoryInterface::class);
        $this->productStockService = $this->createMock(ProductStockService::class);
        $this->decrementProductStockService = $this->createMock(DecrementProductStockService::class);
        $this->addProductToCart = new AddProductToCart(
            $this->cartRepository,
            $this->productStockService,
            $this->decrementProductStockService
        );
    }

    public function testExecuteThrowsExceptionWhenCartNotFound(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Cart not found');

        $cartProduct = $this->createCartProduct();

        $this->cartRepository
            ->expects($this->once())
            ->method('findById')
            ->with($cartProduct->cartId())
            ->willReturn(null);

        $this->addProductToCart->execute($cartProduct);
    }

    public function testExecuteThrowsExceptionWhenProductOutOfStock(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Product out of stock');

        $cartProduct = $this->createCartProduct();
        $cart = $this->createMock(Cart::class);

        $this->cartRepository
            ->expects($this->once())
            ->method('findById')
            ->with($cartProduct->cartId())
            ->willReturn($cart);

        $this->productStockService
            ->expects($this->once())
            ->method('checkStock')
            ->with($cartProduct->productId(), $cartProduct->quantity())
            ->willReturn(false);

        $this->addProductToCart->execute($cartProduct);
    }

    public function testExecuteAddsProductToCart(): void
    {
        $cartProduct = $this->createCartProduct();
        $cart = $this->createMock(Cart::class);

        $this->cartRepository
            ->expects($this->once())
            ->method('findById')
            ->with($cartProduct->cartId())
            ->willReturn($cart);

        $this->productStockService
            ->expects($this->once())
            ->method('checkStock')
            ->with($cartProduct->productId(), $cartProduct->quantity())
            ->willReturn(true);

        $this->decrementProductStockService
            ->expects($this->once())
            ->method('execute')
            ->with($cartProduct->productId(), $cartProduct->quantity());

        $cart
            ->expects($this->once())
            ->method('addProduct')
            ->with($cartProduct)
            ->willReturn($cartProduct);

        $this->cartRepository
            ->expects($this->once())
            ->method('saveProduct')
            ->with($cartProduct);

        $this->addProductToCart->execute($cartProduct);
    }

    private function createCartProduct(): CartProduct
    {
        $cartId = IntValueObject::fromInt(1);
        $productId = IntValueObject::fromInt(2);
        $quantity = IntValueObject::fromInt(3);
        $name = StringValueObject::fromString('Product Name');
        $price = FloatValueObject::from(10.0);

        return new CartProduct($cartId, $productId, $quantity, $name, $price);
    }
}