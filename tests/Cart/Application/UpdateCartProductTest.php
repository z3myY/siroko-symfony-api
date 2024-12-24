<?php

declare(strict_types=1);

use App\Cart\Application\Update\UpdateCartProduct;
use App\Cart\Domain\Entity\Cart;
use App\Cart\Domain\Entity\CartProduct;
use App\Cart\Domain\Repository\CartRepositoryInterface;
use App\Product\Domain\Service\IncrementProductStockService;
use App\Shared\Domain\ValueObject\FloatValueObject;
use App\Shared\Domain\ValueObject\IntValueObject;
use App\Shared\Domain\ValueObject\StringValueObject;
use PHPUnit\Framework\TestCase;

class UpdateCartProductTest extends TestCase
{
    private CartRepositoryInterface $cartRepository;
    private IncrementProductStockService $incrementProductStockService;
    private $updateCartProduct;

    protected function setUp(): void
    {
        $this->cartRepository = $this->createMock(CartRepositoryInterface::class);
        $this->incrementProductStockService = $this->createMock(IncrementProductStockService::class);
        $this->updateCartProduct = new UpdateCartProduct($this->cartRepository, $this->incrementProductStockService);
    }

    public function testExecuteThrowsExceptionWhenCartNotFound(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Cart not found');

        $cartId = IntValueObject::fromInt(1);
        $productId = IntValueObject::fromInt(2);
        $newQuantity = IntValueObject::fromInt(3);
        $name = StringValueObject::fromString('Product Name');
        $price = FloatValueObject::from(10.0);

        $this->cartRepository
            ->expects($this->once())
            ->method('findById')
            ->with($cartId)
            ->willReturn(null);

        $this->updateCartProduct->execute($cartId, $productId, $newQuantity, $name, $price);
    }

    public function testExecuteThrowsExceptionWhenProductNotFoundInCart(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Product not found in cart');

        $cartId = IntValueObject::fromInt(1);
        $productId = IntValueObject::fromInt(2);
        $newQuantity = IntValueObject::fromInt(3);
        $name = StringValueObject::fromString('Product Name');
        $price = FloatValueObject::from(10.0);

        $cart = $this->createMock(Cart::class);
        $cart->method('getProduct')->with($productId)->willReturn(null);

        $this->cartRepository
            ->expects($this->once())
            ->method('findById')
            ->with($cartId)
            ->willReturn($cart);

        $this->updateCartProduct->execute($cartId, $productId, $newQuantity, $name, $price);
    }

    public function testExecuteUpdatesCartProduct(): void
    {
        $cartId = IntValueObject::fromInt(1);
        $productId = IntValueObject::fromInt(2);
        $newQuantity = IntValueObject::fromInt(3);
        $name = StringValueObject::fromString('Updated Product Name');
        $price = FloatValueObject::from(20.0);
        $initialQuantity = IntValueObject::fromInt(5);
        $newStock = IntValueObject::fromInt(2);

        $cartProduct = $this->createMock(CartProduct::class);
        $cartProduct->method('quantity')->willReturn($initialQuantity);

        $cart = $this->createMock(Cart::class);
        $cart->method('getProduct')->with($productId)->willReturn($cartProduct);

        $this->cartRepository
            ->expects($this->once())
            ->method('findById')
            ->with($cartId)
            ->willReturn($cart);

        $this->incrementProductStockService
            ->expects($this->once())
            ->method('execute')
            ->with($productId, $newStock);

        $cartProduct
            ->expects($this->once())
            ->method('update')
            ->with($newQuantity, $name, $price);

        $this->cartRepository
            ->expects($this->once())
            ->method('saveProduct')
            ->with($cartProduct);

        $this->updateCartProduct->execute($cartId, $productId, $newQuantity, $name, $price);
    }
}