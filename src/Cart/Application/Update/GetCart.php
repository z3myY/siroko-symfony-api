<?php

declare(strict_types=1);

namespace App\Cart\Application\Update;

use App\Cart\Domain\Repository\CartRepositoryInterface;
use App\Shared\Domain\ValueObject\IntValueObject;

final class GetCart
{
    public function __construct(private CartRepositoryInterface $cartRepository)
    {
    }

    public function execute(IntValueObject $cartId): array
    {
        $cart = $this->cartRepository->findById($cartId);

        if (!$cart) {
            throw new \Exception('Cart not found');
        }

        return $cart->serialize();
    }
}