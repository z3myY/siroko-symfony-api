<?php

declare(strict_types=1);

namespace App\Cart\Application\Update;

use App\Cart\Domain\Entity\CartProduct;
use App\Cart\Domain\Repository\CartRepositoryInterface;

final class AddProductToCart
{
    public function __construct(private CartRepositoryInterface $cartRepository)
    {
    }

    public function execute(CartProduct $cartProduct): void
    {
        $cart = $this->cartRepository->findById($cartProduct->cartId());

        if ($cart === null) {
            throw new \Exception('Cart not found');
        }

        $cart->addProduct($cartProduct);
        $this->cartRepository->save($cartProduct);
    }
}