<?php
namespace Tests\Cart\Domain\Entity;

use App\Cart\Domain\Entity\Cart;
use App\Cart\Domain\Entity\CartProduct;
use App\Shared\Domain\ValueObject\IntValueObject;
use PHPUnit\Framework\TestCase;
use App\Shared\Domain\ValueObject\StringValueObject;
use App\Shared\Domain\ValueObject\FloatValueObject;

/**
 * Class CartTest
 * @package Tests\Cart\Domain\Entity
 */
class CartTest extends TestCase
{
    private $cartId;
    private $userId;
    private $productId;
    private $quantity;
    private $name;
    private $price;

    protected function setUp(): void
    {
        $this->cartId = IntValueObject::fromInt(5);
        $this->userId = IntValueObject::fromInt(7);
        $this->productId = IntValueObject::fromInt(1);
        $this->quantity = IntValueObject::fromInt(2);
        $this->name = StringValueObject::fromString('Product 1');
        $this->price = FloatValueObject::from(10.5);
    }

    public function testAddProduct(): void
    {
        $cart = new Cart($this->cartId, $this->userId);
        $product = new CartProduct($this->cartId, $this->productId, $this->quantity, $this->name, $this->price);

        $cart->addProduct($product);

        $this->assertSame(2, $cart->totalProducts()->value());
        $this->assertSame(1, $cart->products()[0]->productId()->value());
        $this->assertSame(2, $cart->products()[0]->quantity()->value());
    }

    public function testAddSameProduct(): void
    {
        $cart = new Cart($this->cartId, $this->userId);
        $product = new CartProduct($this->cartId, $this->productId, $this->quantity, $this->name, $this->price);

        $cart->addProduct($product);
        $cart->addProduct($product);

        $this->assertSame(4, $cart->totalProducts()->value());
        $this->assertSame(1, $cart->products()[0]->productId()->value());
        $this->assertSame(4, $cart->products()[0]->quantity()->value());
    }

    public function testRemoveProduct(): void
    {
        $productId2 = IntValueObject::fromInt(2);

        $cart = new Cart($this->cartId, $this->userId);
        $product1 = new CartProduct($this->cartId, $this->productId, $this->quantity, $this->name, $this->price);
        $product2 = new CartProduct($this->cartId, $productId2, $this->quantity, $this->name, $this->price);

        $cart->addProduct($product1);
        $cart->addProduct($product2);

        $cart->removeProduct($this->productId);

        $this->assertSame(2, $cart->totalProducts()->value());
        $this->assertSame(2, $cart->products()[0]->productId()->value());
    }

    public function testClearProducts(): void
    {
        $cart = new Cart($this->cartId, $this->userId);
        $product = new CartProduct($this->cartId, $this->productId, $this->quantity, $this->name, $this->price);

        $cart->addProduct($product);
        $cart->clearProducts();

        $this->assertSame(0, $cart->totalProducts()->value());
        $this->assertEmpty($cart->products());
    }

    public function testSerialize(): void
    {
        $cart = new Cart($this->cartId, $this->userId);
        $product = new CartProduct($this->cartId, $this->productId, $this->quantity, $this->name, $this->price);

        $cart->addProduct($product);

        $expected = [
            'id' => 5,
            'userId' => 7,
            'products' => [
                [
                    'cartId' => 5,
                    'productId' => 1,
                    'quantity' => 2,
                    'name' => 'Product 1',
                    'price' => 10.5
                ]
            ],
            'totalProducts' => 2,
            'totalPrice' => 21.0
        ];

        $this->assertSame($expected, $cart->serialize());
    }
}
