<?php

namespace App\Cart\Application\Update;

use App\Cart\Domain\Repository\CartRepositoryInterface;
use App\Shared\Domain\ValueObject\IntValueObject;

class UpdateCartProduct
{

    public function __construct(private CartRepositoryInterface $cartRepository)
    {
    }

    public function execute(IntValueObject $cartId, IntValueObject $productId, IntValueObject $quantity): void
    {
        $cart = $this->cartRepository->findById($cartId);

        if (!$cart) {
            throw new \Exception('Cart not found');
        }

        $cartProduct = $cart->getProduct($productId);

        if (!$cartProduct) {
            throw new \Exception('Product not found in cart');
        }

        $cartProduct->updateQuantity($quantity);
        $this->cartRepository->save($cartProduct);
    }
}