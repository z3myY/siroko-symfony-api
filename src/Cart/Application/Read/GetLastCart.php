<?php

declare(strict_types=1);

namespace App\Cart\Application\Read;

use App\Cart\Domain\Repository\CartRepositoryInterface;

/**
 * Class GetLastCart
 * @package App\Cart\Application\Read
 */
final class GetLastCart
{
    public function __construct(private CartRepositoryInterface $cartRepository)
    {
    }

    public function execute(): array
    {
        $cart = $this->cartRepository->lastCart();

        if (!$cart) {
            throw new \Exception('Cart not found');
        }

        return $cart->serialize();
    }
}