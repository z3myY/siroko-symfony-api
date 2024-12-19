<?php

declare(strict_types=1);

namespace App\Cart\Application\Update;

use App\Cart\Domain\Repository\CartRepositoryInterface;
use App\Shared\Domain\ValueObject\IntValueObject;

final class RemoveProductFromCart
{
    public function __construct(private CartRepositoryInterface $cartRepository)
    {
    }

    public function execute(IntValueObject $cartId, IntValueObject $productId): void
    {
        $cart = $this->cartRepository->findById($cartId);

        if ($cart) {
            $cart->removeProduct($productId);
            $this->cartRepository->save($cart);
        }
    }
}