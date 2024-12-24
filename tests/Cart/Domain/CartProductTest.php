<?php

namespace Tests\Cart\Domain\Entity;

use App\Cart\Domain\Entity\CartProduct;
use App\Shared\Domain\ValueObject\FloatValueObject;
use App\Shared\Domain\ValueObject\IntValueObject;
use App\Shared\Domain\ValueObject\StringValueObject;
use PHPUnit\Framework\TestCase;

class CartProductTest extends TestCase
{
    private $cartId;
    private $productId;
    private $quantity;
    private $name;
    private $price;

    protected function setUp(): void
    {
        $this->cartId = IntValueObject::fromInt(1);
        $this->productId = IntValueObject::fromInt(2);
        $this->quantity = IntValueObject::fromInt(3);
        $this->name = StringValueObject::fromString('Product Name');
        $this->price = FloatValueObject::from(10.0);
    }

    public function testCartProductCreation(): void
    {
        $cartProduct = new CartProduct($this->cartId, $this->productId, $this->quantity, $this->name, $this->price);

        $this->assertSame(1, $cartProduct->cartId()->value());
        $this->assertSame(2, $cartProduct->productId()->value());
        $this->assertSame(3, $cartProduct->quantity()->value());
        $this->assertSame('Product Name', $cartProduct->name()->value());
        $this->assertSame(10.0, $cartProduct->price()->value());
    }

    public function testUpdateQuantity(): void
    {
        $cartProduct = new CartProduct($this->cartId, $this->productId, $this->quantity, $this->name, $this->price);
        $newQuantity = IntValueObject::fromInt(5);

        $cartProduct->updateQuantity($newQuantity);

        $this->assertSame(5, $cartProduct->quantity()->value());
    }

    public function testIncreaseQuantity(): void
    {
        $cartProduct = new CartProduct($this->cartId, $this->productId, $this->quantity, $this->name, $this->price);
        $amount = IntValueObject::fromInt(2);

        $cartProduct->increaseQuantity($amount);

        $this->assertSame(5, $cartProduct->quantity()->value());
    }

    public function testDecreaseQuantity(): void
    {
        $cartProduct = new CartProduct($this->cartId, $this->productId, $this->quantity, $this->name, $this->price);
        $amount = IntValueObject::fromInt(1);

        $cartProduct->decreaseQuantity($amount);

        $this->assertSame(2, $cartProduct->quantity()->value());
    }

    public function testTotalPrice(): void
    {
        $cartProduct = new CartProduct($this->cartId, $this->productId, $this->quantity, $this->name, $this->price);

        $totalPrice = $cartProduct->totalPrice();

        $this->assertSame(30.0, $totalPrice->value());
    }

    public function testUpdateName(): void
    {
        $cartProduct = new CartProduct($this->cartId, $this->productId, $this->quantity, $this->name, $this->price);
        $newName = StringValueObject::fromString('New Product Name');

        $cartProduct->updateName($newName);

        $this->assertSame('New Product Name', $cartProduct->name()->value());
    }

    public function testUpdatePrice(): void
    {
        $cartProduct = new CartProduct($this->cartId, $this->productId, $this->quantity, $this->name, $this->price);
        $newPrice = FloatValueObject::from(15.0);

        $cartProduct->updatePrice($newPrice);

        $this->assertSame(15.0, $cartProduct->price()->value());
    }

    public function testUpdate(): void
    {
        $cartProduct = new CartProduct($this->cartId, $this->productId, $this->quantity, $this->name, $this->price);
        $newQuantity = IntValueObject::fromInt(5);
        $newName = StringValueObject::fromString('New Product Name');
        $newPrice = FloatValueObject::from(15.0);

        $cartProduct->update($newQuantity, $newName, $newPrice);

        $this->assertSame(5, $cartProduct->quantity()->value());
        $this->assertSame('New Product Name', $cartProduct->name()->value());
        $this->assertSame(15.0, $cartProduct->price()->value());
    }

    public function testSerialize(): void
    {
        $cartProduct = new CartProduct($this->cartId, $this->productId, $this->quantity, $this->name, $this->price);

        $expected = [
            'cartId' => 1,
            'productId' => 2,
            'quantity' => 3,
            'name' => 'Product Name',
            'price' => 10.0,
        ];

        $this->assertSame($expected, $cartProduct->serialize());
    }
}