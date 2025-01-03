<?php

declare(strict_types=1);

namespace App\Order\Application\Create;

use App\Order\Domain\Entity\Order;
use App\Order\Domain\Entity\OrderProducts;
use App\Order\Domain\Repository\OrderRepositoryInterface;
use App\Order\Domain\Repository\OrderProductsRepositoryInterface;
use App\Order\Infrastructure\Service\CartService;
use App\Shared\Domain\ValueObject\FloatValueObject;
use App\Shared\Domain\ValueObject\IntValueObject;
use App\Shared\Domain\ValueObject\StringValueObject;

class CreateOrder
{
    public function __construct(
        private CartService $cartService,
        private OrderRepositoryInterface $orderRepositoryInterface,
        private OrderProductsRepositoryInterface $orderProductsRepositoryInterface,
    )
    {
    }

    public function execute(int $userId, int $cartId): void
    {
        $cartData = $this->cartService->getCart($cartId);
        $order = Order::load(IntValueObject::fromInt($userId), $cartData['products']);

        $orderId = $this->orderRepositoryInterface->save($order);

        foreach ($cartData['products'] as $product) {
            $orderProduct = OrderProducts::load(
                IntValueObject::fromInt($orderId),
                IntValueObject::fromInt($product['productId']),
                StringValueObject::fromString($product['name']),
                FloatValueObject::from($product['price']),
                IntValueObject::fromInt($product['quantity'])
            );
            $this->orderProductsRepositoryInterface->save($orderProduct);
        }
    }

}