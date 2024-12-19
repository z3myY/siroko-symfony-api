<?php
namespace Tests\Cart\Domain\Entity;

use App\Cart\Domain\Entity\Cart;
use App\Cart\Domain\Entity\CartProduct;
use App\Shared\Domain\ValueObject\IntValueObject;
use PHPUnit\Framework\TestCase;

/**
 * Class CartTest
 * @package Tests\Cart\Domain\Entity
 */
class CartTest extends TestCase
{
    public function testAddProduct(): void
    {
        $cartId = IntValueObject::fromInt(5);
        $userId = IntValueObject::fromInt(7);
        $productId = IntValueObject::fromInt(1);    
        $quantity = IntValueObject::fromInt(2);


        $cart = new Cart($cartId, $userId);
        $product = new CartProduct($cartId, $productId, $quantity);

        $cart->addProduct($product);

        $this->assertSame(1, $cart->totalProducts()->value());
        $this->assertSame(1, $cart->products()[0]->productId()->value());
        $this->assertSame(2, $cart->products()[0]->quantity()->value());
    }

    public function testAddSameProduct(): void
    {
        $cartId = IntValueObject::fromInt(5);
        $userId = IntValueObject::fromInt(7);
        $productId = IntValueObject::fromInt(1);
        $quantity = IntValueObject::fromInt(2);

        $cart = new Cart($cartId, $userId);
        $product = new CartProduct($cartId, $productId, $quantity);

        $cart->addProduct($product);
        $cart->addProduct($product);

        $this->assertSame(1, $cart->totalProducts()->value());
        $this->assertSame(1, $cart->products()[0]->productId()->value());
        $this->assertSame(4, $cart->products()[0]->quantity()->value());
    }

    public function testRemoveProduct(): void
    {
        $cartId = IntValueObject::fromInt(5);
        $userId = IntValueObject::fromInt(7);
        $productId1 = IntValueObject::fromInt(1);
        $productId2 = IntValueObject::fromInt(2);
        $quantity = IntValueObject::fromInt(2);

        $cart = new Cart($cartId, $userId);
        $product1 = new CartProduct($cartId, $productId1, $quantity);
        $product2 = new CartProduct($cartId, $productId2, $quantity);

        $cart->addProduct($product1);
        $cart->addProduct($product2);

        $cart->removeProduct($productId1);

        $this->assertSame(1, $cart->totalProducts()->value());
        $this->assertSame(2, $cart->products()[0]->productId()->value());
    }

    public function testClearProducts(): void
    {
        $cartId = IntValueObject::fromInt(5);
        $userId = IntValueObject::fromInt(7);
        $productId = IntValueObject::fromInt(1);
        $quantity = IntValueObject::fromInt(2);

        $cart = new Cart($cartId, $userId);
        $product = new CartProduct($cartId, $productId, $quantity);

        $cart->addProduct($product);
        $cart->clearProducts();

        $this->assertSame(0, $cart->totalProducts()->value());
        $this->assertEmpty($cart->products());
    }

    public function testSerialize(): void
    {
        $cartId = IntValueObject::fromInt(5);
        $userId = IntValueObject::fromInt(7);
        $productId = IntValueObject::fromInt(1);
        $quantity = IntValueObject::fromInt(2);

        $cart = new Cart($cartId, $userId);
        $product = new CartProduct($cartId, $productId, $quantity);

        $cart->addProduct($product);

        $expected = [
            'id' => 5,
            'userId' => 7,
            'products' => [
                [
                    'cartId' => 5,
                    'productId' => 1,
                    'quantity' => 2
                ]
            ]
        ];

        $this->assertSame($expected, $cart->serialize());
    }
}