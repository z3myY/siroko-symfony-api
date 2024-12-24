<?php

declare(strict_types=1);

namespace App\Order\Application;

use App\Order\Application\Create\CreateOrder;
use PHPUnit\Framework\TestCase;
use App\Order\Domain\Entity\Order;
use App\Order\Domain\Entity\OrderProducts;
use App\Order\Domain\Repository\OrderRepositoryInterface;
use App\Order\Domain\Repository\OrderProductsRepositoryInterface;
use App\Order\Infrastructure\Service\CartService;
use App\Shared\Domain\ValueObject\IntValueObject;

class CreateOrderTest extends TestCase
{
    private CartService $cartService;
    private OrderRepositoryInterface $orderRepository;
    private OrderProductsRepositoryInterface $orderProductsRepository;
    private $createOrder;


    protected function setUp(): void
    {
        $this->cartService = $this->createMock(CartService::class);
        $this->orderRepository = $this->createMock(OrderRepositoryInterface::class);
        $this->orderProductsRepository = $this->createMock(OrderProductsRepositoryInterface::class);

        $this->createOrder = new CreateOrder(
            $this->cartService,
            $this->orderRepository,
            $this->orderProductsRepository
        );
    }

    public function testExecuteCreatesOrder(): void
    {
        $userId = IntValueObject::fromInt(1);
        $cartId = 1;
        $cartData = [
            'products' => [
                ['productId' => 1, 'quantity' => 2, 'price' => 10.0, 'name' => 'Product 1'],
                ['productId' => 2, 'quantity' => 1, 'price' => 20.0, 'name' => 'Product 2'],
            ],
        ];

        $this->cartService
            ->expects($this->once())
            ->method('getCart')
            ->with($cartId)
            ->willReturn($cartData);

        $this->orderRepository
            ->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(Order::class))
            ->willReturn(1);

        $this->orderProductsRepository
            ->expects($this->exactly(2))
            ->method('save')
            ->willReturnOnConsecutiveCalls(
                $this->callback(function (OrderProducts $orderProduct) use ($cartData) {
                    return $orderProduct->productId()->value() === $cartData['products'][0]['productId'] &&
                           $orderProduct->quantity()->value() === $cartData['products'][0]['quantity'] &&
                           $orderProduct->price()->value() === $cartData['products'][0]['price'];
                }),
                $this->callback(function (OrderProducts $orderProduct) use ($cartData) {
                    return $orderProduct->productId()->value() === $cartData['products'][1]['productId'] &&
                           $orderProduct->quantity()->value() === $cartData['products'][1]['quantity'] &&
                           $orderProduct->price()->value() === $cartData['products'][1]['price'];
                })
            );

        $this->createOrder->execute($userId->value(), $cartId);
    }

    public function testExecuteThrowsExceptionWhenCartNotFound(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Cart not found');

        $userId = 1;
        $cartId = 1;

        $this->cartService
            ->expects($this->once())
            ->method('getCart')
            ->with($cartId)
            ->willThrowException(new \Exception('Cart not found'));

        $this->createOrder->execute($userId, $cartId);
    }

    public function testExecuteThrowsExceptionWhenOrderNotSaved(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Order not saved');

        $userId = 1;
        $cartId = 1;
        $cartData = [
            'products' => [
                [
                    'productId' => 1,
                    'name' => 'Product 1',
                    'price' => 100.0,
                    'quantity' => 2
                ]
            ]
        ];

        $this->cartService
            ->expects($this->once())
            ->method('getCart')
            ->with($cartId)
            ->willReturn($cartData);

        $this->orderRepository
            ->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(Order::class))
            ->willThrowException(new \Exception('Order not saved'));

        $this->createOrder->execute($userId, $cartId);
    }
}
