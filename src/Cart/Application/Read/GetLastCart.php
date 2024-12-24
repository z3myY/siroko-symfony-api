<?php

declare(strict_types=1);

namespace App\Cart\Application\Read;

use App\Cart\Application\Create\AddCart;
use App\Cart\Domain\Repository\CartRepositoryInterface;

/**
 * Class GetLastCart
 * @package App\Cart\Application\Read
 */
class GetLastCart
{
    public function __construct(
        private CartRepositoryInterface $cartRepository,
        private AddCart $addCart
        )
    {
    }

    public function execute(): array
    {
        $cart = $this->cartRepository->lastCart();

        if (!$cart) {
            $cart = $this->addCart->execute(1);
        }
        
        return $cart->serialize();
    }
}