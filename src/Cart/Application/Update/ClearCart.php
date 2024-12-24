<?php

declare(strict_types=1);

namespace App\Cart\Application\Update;

use App\Cart\Domain\Repository\CartRepositoryInterface;
use App\Shared\Domain\ValueObject\IntValueObject;

class ClearCart
{
    public function __construct(private CartRepositoryInterface $cartRepository)
    {
    }

    public function execute(int $cartId): void
    {
        $cart = $this->cartRepository->findById(IntValueObject::fromInt($cartId));

        if ($cart) {
            $cart->clearProducts();
            $this->cartRepository->clear($cart);
        }
    }
}