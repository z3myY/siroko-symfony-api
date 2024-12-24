<?php

declare(strict_types=1);

namespace App\Tests\Cart\Application;

use App\Cart\Application\Create\AddCart;
use App\Cart\Domain\Entity\Cart;
use App\Cart\Domain\Repository\CartRepositoryInterface;
use App\Shared\Domain\ValueObject\IntValueObject;
use PHPUnit\Framework\TestCase;

class AddCartTest extends TestCase
{
    /** @var CartRepositoryInterface|MockObject */
    private $cartRepository;
    private AddCart $addCart;

    protected function setUp(): void
    {
        $this->cartRepository = $this->createMock(CartRepositoryInterface::class);
        $this->addCart = new AddCart($this->cartRepository);
    }

    public function testExecuteReturnsExistingCart(): void
    {
        $userId = 1;
        $userIdValueObject = IntValueObject::fromInt($userId);
        $existingCart = $this->createMock(Cart::class);

        $this->cartRepository
            ->expects($this->once())
            ->method('findByUserId')
            ->with($userIdValueObject)
            ->willReturn($existingCart);

        $result = $this->addCart->execute($userId);

        $this->assertSame($existingCart, $result);
    }

    public function testExecuteCreatesNewCart(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Cart not created');

        $userId = 1;
        $userIdValueObject = IntValueObject::fromInt($userId);

        $this->cartRepository
            ->expects($this->exactly(2))
            ->method('findByUserId')
            ->with($userIdValueObject)
            ->willReturnOnConsecutiveCalls(null, null);

        $this->cartRepository
            ->expects($this->once())
            ->method('create')
            ->with($this->isInstanceOf(Cart::class))
            ->willReturn(1);

        $this->addCart->execute($userId);
    }

    public function testExecuteThrowsExceptionWhenCartNotCreated(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Cart not created');

        $userId = 1;
        $userIdValueObject = IntValueObject::fromInt($userId);

        $this->cartRepository
            ->expects($this->exactly(2))
            ->method('findByUserId')
            ->with($userIdValueObject)
            ->willReturn(null);

        $this->cartRepository
            ->expects($this->once())
            ->method('create')
            ->with($this->isInstanceOf(Cart::class))
            ->willReturn(1);

        $this->addCart->execute($userId);
    }
}