<?php

declare(strict_types=1);

namespace Tests\Cart\Application\Update;

use App\Cart\Application\Update\ClearCart;
use App\Cart\Domain\Entity\Cart;
use App\Cart\Domain\Repository\CartRepositoryInterface;
use App\Shared\Domain\ValueObject\IntValueObject;
use PHPUnit\Framework\TestCase;

class ClearCartTest extends TestCase
{
    private CartRepositoryInterface $cartRepository;
    private ClearCart $clearCart;

    protected function setUp(): void
    {
        $this->cartRepository = $this->createMock(CartRepositoryInterface::class);
        $this->clearCart = new ClearCart($this->cartRepository);
    }

    public function testExecuteClearsCartWhenCartFound(): void
    {
        $cartId = IntValueObject::fromInt(1);
        $cart = $this->createMock(Cart::class);

        $this->cartRepository
            ->expects($this->once())
            ->method('findById')
            ->with($cartId)
            ->willReturn($cart);

        $cart
            ->expects($this->once())
            ->method('clearProducts');

        $this->cartRepository
            ->expects($this->once())
            ->method('clear')
            ->with($cart);

        $this->clearCart->execute(1);
    }

    public function testExecuteDoesNothingWhenCartNotFound(): void
    {
        $cartId = IntValueObject::fromInt(1);

        $this->cartRepository
            ->expects($this->once())
            ->method('findById')
            ->with($cartId)
            ->willReturn(null);

        $this->cartRepository
            ->expects($this->never())
            ->method('clear');

        $this->clearCart->execute(1);
    }
}