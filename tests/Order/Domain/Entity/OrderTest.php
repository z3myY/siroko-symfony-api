<?php

declare(strict_types=1);

namespace Tests\Order\Domain\Entity;

use App\Order\Domain\Entity\Order;
use App\Order\Domain\Entity\OrderProducts;
use App\Shared\Domain\ValueObject\FloatValueObject;
use App\Shared\Domain\ValueObject\IntValueObject;
use PHPUnit\Framework\TestCase;

class OrderTest extends TestCase
{
    public function testOrderCreation(): void
    {
        $userId = IntValueObject::fromInt(1);
        $products = $this->createProducts();
        $order = new Order($userId, $products);
    
        $this->assertSame($userId, $order->userId());
        $this->assertNull($order->id());
        $this->assertSame($products, $order->products());
    }
    
    public function testOrderCreationWithId(): void
    {
        $userId = IntValueObject::fromInt(1);
        $products = $this->createProducts();
        $id = IntValueObject::fromInt(1);
        $order = new Order($userId, $products, $id);
    
        $this->assertSame($userId, $order->userId());
        $this->assertSame($id, $order->id());
        $this->assertSame($products, $order->products());
    }
    
    public function testLoad(): void
    {
        $userId = IntValueObject::fromInt(1);
        $products = $this->createProducts();
        $id = IntValueObject::fromInt(1);
        $order = Order::load($userId, $products, $id);
    
        $this->assertSame($userId, $order->userId());
        $this->assertSame($id, $order->id());
        $this->assertSame($products, $order->products());
    }
    
    private function createProducts(): array
    {
        $product1 = $this->createMock(OrderProducts::class);
        $product1->method('serialize')->willReturn([
            'productId' => 1,
            'name' => 'Product 1',
            'price' => 10.0,
            'quantity' => 2
        ]);
    
        $product2 = $this->createMock(OrderProducts::class);
        $product2->method('serialize')->willReturn([
            'productId' => 2,
            'name' => 'Product 2',
            'price' => 20.0,
            'quantity' => 1
        ]);
    
        return [$product1, $product2];
    }
}