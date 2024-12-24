<?php

declare(strict_types=1);

namespace App\Order\Domain\Entity;

use PHPUnit\Framework\TestCase;
use App\Order\Domain\Entity\OrderProducts;
use App\Shared\Domain\ValueObject\FloatValueObject;
use App\Shared\Domain\ValueObject\IntValueObject;
use App\Shared\Domain\ValueObject\StringValueObject;

class OrderProductsTest extends TestCase
{
    public function testOrderProductsCreation()
    {
        $orderId = IntValueObject::fromInt(1);
        $productId = IntValueObject::fromInt(2);
        $name = StringValueObject::fromString('Product Name');
        $price = FloatValueObject::from(10.0);
        $quantity = IntValueObject::fromInt(3);

        $orderProducts = new OrderProducts($orderId, $productId, $name, $price, $quantity);

        $this->assertInstanceOf(OrderProducts::class, $orderProducts);
        $this->assertEquals($orderId, $orderProducts->orderId());
        $this->assertEquals($productId, $orderProducts->productId());
        $this->assertEquals($name, $orderProducts->name());
        $this->assertEquals($price, $orderProducts->price());
        $this->assertEquals($quantity, $orderProducts->quantity());
    }

    public function testTotalPriceCalculation()
    {
        $price = FloatValueObject::from(10.0);
        $quantity = IntValueObject::fromInt(3);
        $orderProducts = new OrderProducts(
            IntValueObject::fromInt(1),
            IntValueObject::fromInt(2),
            StringValueObject::fromString('Product Name'),
            $price,
            $quantity
        );

        $expectedTotalPrice = FloatValueObject::from(30.0);
        $this->assertEquals($expectedTotalPrice, $orderProducts->totalPrice());
    }

    public function testLoadMethod()
    {
        $orderId = IntValueObject::fromInt(1);
        $productId = IntValueObject::fromInt(2);
        $name = StringValueObject::fromString('Product Name');
        $price = FloatValueObject::from(10.0);
        $quantity = IntValueObject::fromInt(3);

        $orderProducts = OrderProducts::load($orderId, $productId, $name, $price, $quantity);

        $this->assertInstanceOf(OrderProducts::class, $orderProducts);
        $this->assertEquals($orderId, $orderProducts->orderId());
        $this->assertEquals($productId, $orderProducts->productId());
        $this->assertEquals($name, $orderProducts->name());
        $this->assertEquals($price, $orderProducts->price());
        $this->assertEquals($quantity, $orderProducts->quantity());
    }

    public function testSerializeMethod()
    {
        $orderId = IntValueObject::fromInt(1);
        $productId = IntValueObject::fromInt(2);
        $name = StringValueObject::fromString('Product Name');
        $price = FloatValueObject::from(10.0);
        $quantity = IntValueObject::fromInt(3);

        $orderProducts = new OrderProducts($orderId, $productId, $name, $price, $quantity);

        $expectedSerialization = [
            'productId' => 2,
            'name' => 'Product Name',
            'price' => 10.0,
            'quantity' => 3,
            'totalPrice' => 30.0
        ];

        $this->assertEquals($expectedSerialization, $orderProducts->serialize());
    }
}