<?php

namespace Tests\Cart\Application\Read;

use App\Cart\Application\Read\GetAllCarts;
use App\Cart\Domain\Entity\Cart;
use App\Cart\Domain\Repository\CartRepositoryInterface;
use App\Cart\Domain\Entity\CartProduct;
use PHPUnit\Framework\TestCase;
use App\Shared\Domain\ValueObject\IntValueObject;
use App\Shared\Domain\ValueObject\StringValueObject;
use App\Shared\Domain\ValueObject\FloatValueObject;

class GetAllCartsTest extends TestCase
{
    private CartRepositoryInterface $cartRepository;
    private $getAllCarts;

    protected function setUp(): void
    {
        $this->cartRepository = $this->createMock(CartRepositoryInterface::class);
        $this->getAllCarts = new GetAllCarts($this->cartRepository);
    }

    public function testExecuteReturnsSerializedCarts(): void
    {
        $cartId = IntValueObject::fromInt(1);
        $userId = IntValueObject::fromInt(2);
        $productId = IntValueObject::fromInt(3);
        $quantity = IntValueObject::fromInt(4);
        $name = StringValueObject::fromString('Product Name');
        $price = FloatValueObject::from(10.0);

        $cartProduct = new CartProduct($cartId, $productId, $quantity, $name, $price);
        $cart = new Cart($cartId, $userId);
        $cart->addProduct($cartProduct);

        $this->cartRepository
            ->expects($this->once())
            ->method('findAll')
            ->willReturn([$cart]);

        $result = $this->getAllCarts->execute();

        $expected = [
            [
                'id' => 1,
                'userId' => 2,
                'products' => [
                    [
                        'cartId' => 1,
                        'productId' => 3,
                        'quantity' => 4,
                        'name' => 'Product Name',
                        'price' => 10.0,
                    ]
                ],
                'totalProducts' => 4,
                'totalPrice' => 40.0,
            ]
        ];

        $this->assertSame($expected, $result);
    }

    public function testExecuteThrowsExceptionWhenCartsNotFound(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Carts not found');

        $this->cartRepository
            ->expects($this->once())
            ->method('findAll')
            ->willReturn([]);

        $this->getAllCarts->execute();
    }
}