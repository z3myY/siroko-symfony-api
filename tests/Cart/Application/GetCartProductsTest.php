<?php

namespace Tests\Cart\Application\Read;

use App\Cart\Application\Read\GetCartProducts;
use App\Cart\Domain\Entity\Cart;
use App\Cart\Domain\Entity\CartProduct;
use App\Cart\Domain\Repository\CartRepositoryInterface;
use App\Shared\Domain\ValueObject\IntValueObject;
use App\Shared\Domain\ValueObject\StringValueObject;
use App\Shared\Domain\ValueObject\FloatValueObject;
use PHPUnit\Framework\TestCase;

class GetCartProductsTest extends TestCase
{
    private CartRepositoryInterface $cartRepository;
    private $getCartProducts;

    protected function setUp(): void
    {
        $this->cartRepository = $this->createMock(CartRepositoryInterface::class);
        $this->getCartProducts = new GetCartProducts($this->cartRepository);
    }

    public function testExecuteReturnsSerializedProducts(): void
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
            ->method('findById')
            ->with($cartId)
            ->willReturn($cart);

        $result = $this->getCartProducts->execute(1);

        $expected = [
            [
                'cartId' => 1,
                'productId' => 3,
                'quantity' => 4,
                'name' => 'Product Name',
                'price' => 10.0,
            ]
        ];

        $this->assertSame($expected, $result);
    }

    public function testExecuteReturnsEmptyArrayWhenNoProducts(): void
    {
        $cartId = IntValueObject::fromInt(1);
        $userId = IntValueObject::fromInt(2);
        $cart = new Cart($cartId, $userId);

        $this->cartRepository
            ->expects($this->once())
            ->method('findById')
            ->with($cartId)
            ->willReturn($cart);

        $result = $this->getCartProducts->execute(1);

        $this->assertEmpty($result);
    }
}