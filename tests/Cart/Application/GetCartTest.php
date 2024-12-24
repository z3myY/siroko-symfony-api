<?php

namespace Tests\Cart\Application\Read;

use App\Cart\Application\Read\GetCart;
use App\Cart\Domain\Entity\Cart;
use App\Cart\Domain\Repository\CartRepositoryInterface;
use App\Shared\Domain\ValueObject\IntValueObject;
use PHPUnit\Framework\TestCase;


class GetCartTest extends TestCase
{
    private CartRepositoryInterface $cartRepository;
    private $getCart;

    protected function setUp(): void
    {
        $this->cartRepository = $this->createMock(CartRepositoryInterface::class);
        $this->getCart = new GetCart($this->cartRepository);
    }

    public function testExecuteReturnsSerializedCart(): void
    {
        $cartId = IntValueObject::fromInt(1);
        $userId = IntValueObject::fromInt(2);
        $cart = new Cart($cartId, $userId);

        $this->cartRepository
            ->expects($this->once())
            ->method('findById')
            ->with($cartId)
            ->willReturn($cart);

        $result = $this->getCart->execute(1);

        $expected = [
            'id' => 1,
            'userId' => 2,
            'products' => [],
            'totalProducts' => 0,
            'totalPrice' => 0.0,
        ];

        $this->assertSame($expected, $result);
    }

    public function testExecuteThrowsExceptionWhenCartNotFound(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Cart not found');

        $cartId = IntValueObject::fromInt(1);

        $this->cartRepository
            ->expects($this->once())
            ->method('findById')
            ->with($cartId)
            ->willReturn(null);

        $this->getCart->execute(1);
    }
}