<?php

declare(strict_types=1);

namespace App\Cart\Application\Read;

use App\Cart\Domain\Repository\CartRepositoryInterface;
use App\Shared\Domain\ValueObject\IntValueObject;

/**
 * Class GetCart
 * @package App\Cart\Application\Read
 */
class GetCart
{
    public function __construct(private CartRepositoryInterface $cartRepository)
    {
    }

    public function execute(int $cartId): array
    {
        $cart = $this->cartRepository->findById(IntValueObject::fromInt($cartId));

        if (!$cart) {
            throw new \Exception('Cart not found');
        }

        return $cart->serialize();
    }
}