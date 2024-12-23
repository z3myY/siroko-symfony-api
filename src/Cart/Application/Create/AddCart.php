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
        if ($this->cartRepository->findByUserId(IntValueObject::fromInt($userId)) !== null) {
            throw new \Exception('Cart already exists for this user');
        }
        $userId = IntValueObject::fromInt($userId);
        $cart = Cart::load(null, $userId);
        $cart = $this->cartRepository->create($cart);

        if ($cart === null) {
            throw new \Exception('Cart not created');
        }
        return $cart >= 1;
    }
}