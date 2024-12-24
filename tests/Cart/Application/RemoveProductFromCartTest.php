<?php

namespace Tests\Cart\Application;

use App\Cart\Application\Update\RemoveProductFromCart;
use App\Cart\Domain\Entity\Cart;
use App\Cart\Domain\Entity\CartProduct;
use App\Cart\Domain\Repository\CartProductRepositoryInterface;
use App\Cart\Domain\Repository\CartRepositoryInterface;
use App\Product\Domain\Service\IncrementProductStockService;
use App\Shared\Domain\ValueObject\IntValueObject;
use PHPUnit\Framework\TestCase;


class RemoveProductFromCartTest extends TestCase
{
    private CartProductRepositoryInterface $cartProductRepository;
    private CartRepositoryInterface $cartRepository;
    private IncrementProductStockService $incrementProductStockService;
    private $removeProductFromCart;

    protected function setUp(): void
    {
        $this->cartProductRepository = $this->createMock(CartProductRepositoryInterface::class);
        $this->cartRepository = $this->createMock(CartRepositoryInterface::class);
        $this->incrementProductStockService = $this->createMock(IncrementProductStockService::class);
        $this->removeProductFromCart = new RemoveProductFromCart(
            $this->cartProductRepository,
            $this->cartRepository,
            $this->incrementProductStockService
        );
    }

    public function testExecuteRemovesProductFromCart(): void
    {
        $cartId = IntValueObject::fromInt(1);
        $productId = IntValueObject::fromInt(2);
        $quantity = IntValueObject::fromInt(3);

        $cartProduct = $this->createMock(CartProduct::class);
        $cartProduct->method('quantity')->willReturn($quantity);

        $cart = $this->createMock(Cart::class);
        $cart->method('getProduct')->with($productId)->willReturn($cartProduct);

        $this->cartRepository
            ->expects($this->once())
            ->method('findById')
            ->with($cartId)
            ->willReturn($cart);

        $this->cartProductRepository
            ->expects($this->once())
            ->method('find')
            ->with($cartProduct)
            ->willReturn($cartProduct);

        $this->cartProductRepository
            ->expects($this->once())
            ->method('removeProduct')
            ->with($cartProduct);

        $this->incrementProductStockService
            ->expects($this->once())
            ->method('execute')
            ->with($productId, $quantity);

        $this->removeProductFromCart->execute($cartId, $productId);
    }

    public function testExecuteDoesNothingWhenCartNotFound(): void
    {
        $cartId = IntValueObject::fromInt(1);
        $productId = IntValueObject::fromInt(2);

        $this->cartRepository
            ->expects($this->once())
            ->method('findById')
            ->with($cartId)
            ->willReturn(null);

        $this->cartProductRepository
            ->expects($this->never())
            ->method('find');

        $this->cartProductRepository
            ->expects($this->never())
            ->method('removeProduct');

        $this->incrementProductStockService
            ->expects($this->never())
            ->method('execute');

        $this->removeProductFromCart->execute($cartId, $productId);
    }

    public function testExecuteDoesNothingWhenProductNotFoundInCart(): void
    {
        $cartId = IntValueObject::fromInt(1);
        $productId = IntValueObject::fromInt(2);

        $cart = $this->createMock(Cart::class);
        $cart->method('getProduct')->with($productId)->willReturn(null);

        $this->cartRepository
            ->expects($this->once())
            ->method('findById')
            ->with($cartId)
            ->willReturn($cart);

        $this->cartProductRepository
            ->expects($this->never())
            ->method('find');

        $this->cartProductRepository
            ->expects($this->never())
            ->method('removeProduct');

        $this->incrementProductStockService
            ->expects($this->never())
            ->method('execute');

        $this->removeProductFromCart->execute($cartId, $productId);
    }
}