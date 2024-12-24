<?php

declare(strict_types=1);

namespace App\Cart\Application\Read;

use App\Cart\Domain\Repository\CartRepositoryInterface;
use App\Cart\Domain\Entity\Cart;

/**
 * Class GetAllCart
 * @package App\Cart\Application\Read
 */
class GetAllCarts
{
    public function __construct(private CartRepositoryInterface $cartRepository)
    {
    }

    public function execute(): array
    {
        /** @var Cart[] */
        $carts = $this->cartRepository->findAll();

        if (!$carts) {
            throw new \Exception('Carts not found');
        }

        return array_map(fn(Cart $cart) => $cart->serialize(), $carts);
    }
}