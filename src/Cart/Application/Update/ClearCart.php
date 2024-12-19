<?php

declare(strict_types=1);

namespace App\Cart\Application\Update;

use App\Cart\Domain\Repository\CartRepositoryInterface;
use App\Shared\Domain\ValueObject\IntValueObject;

final class ClearCart
{
    public function __construct(private CartRepositoryInterface $cartRepository)
    {
    }

    public function execute(IntValueObject $cartId): void
    {
        $cart = $this->cartRepository->findById($cartId);

        if ($cart) {
            $cart->clearProducts();
            $this->cartRepository->save($cart);
        }
    }
}