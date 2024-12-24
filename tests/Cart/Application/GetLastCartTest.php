<?php

use App\Cart\Application\Read\GetLastCart;
use App\Cart\Application\Create\AddCart;
use App\Cart\Domain\Entity\Cart;
use App\Cart\Domain\Repository\CartRepositoryInterface;
use PHPUnit\Framework\TestCase;
use App\Shared\Domain\ValueObject\IntValueObject;

class GetLastCartTest extends TestCase
{
    private CartRepositoryInterface $cartRepository;
    private AddCart $addCart;
    private $getLastCart;

    protected function setUp(): void
    {
        $this->cartRepository = $this->createMock(CartRepositoryInterface::class);
        $this->addCart = $this->createMock(AddCart::class);
        $this->getLastCart = new GetLastCart($this->cartRepository, $this->addCart);
    }

    public function testExecuteReturnsSerializedLastCart(): void
    {
        $cartId = IntValueObject::fromInt(1);
        $userId = IntValueObject::fromInt(2);
        $cart = new Cart($cartId, $userId);

        $this->cartRepository
            ->expects($this->once())
            ->method('lastCart')
            ->willReturn($cart);

        $result = $this->getLastCart->execute();

        $expected = [
            'id' => 1,
            'userId' => 2,
            'products' => [],
            'totalProducts' => 0,
            'totalPrice' => 0.0,
        ];

        $this->assertSame($expected, $result);
    }

    public function testExecuteCreatesAndReturnsSerializedNewCartWhenNoLastCart(): void
    {
        $cartId = IntValueObject::fromInt(1);
        $userId = IntValueObject::fromInt(2);
        $newCart = new Cart($cartId, $userId);

        $this->cartRepository
            ->expects($this->once())
            ->method('lastCart')
            ->willReturn(null);

        $this->addCart
            ->expects($this->once())
            ->method('execute')
            ->with(1)
            ->willReturn($newCart);

        $result = $this->getLastCart->execute();

        $expected = [
            'id' => 1,
            'userId' => 2,
            'products' => [],
            'totalProducts' => 0,
            'totalPrice' => 0.0,
        ];

        $this->assertSame($expected, $result);
    }
}