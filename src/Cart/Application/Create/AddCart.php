<?php

namespace App\Cart\Application\Create;

use App\Cart\Domain\Entity\Cart;
use App\Cart\Domain\Repository\CartRepositoryInterface;
use App\Shared\Domain\ValueObject\IntValueObject;

/**
 * Class AddCart
 * @package App\Cart\Application\Create
 */
final class AddCart
{
    public function __construct(private CartRepositoryInterface $cartRepository)
    {
    }

    public function execute(int $userId): bool
    {
        $userId = IntValueObject::fromInt($userId);
        $cart = $this->cartRepository->create($userId);

        if ($cart === null) {
            throw new \Exception('Cart not created');
        }
        return $cart >= 1;
    }
}