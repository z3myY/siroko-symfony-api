<?php

namespace App\Cart\Application\Create;

use App\Cart\Domain\Entity\Cart;
use App\Cart\Domain\Repository\CartRepositoryInterface;
use App\Shared\Domain\ValueObject\IntValueObject;

/**
 * Class AddCart
 * @package App\Cart\Application\Create
 */
class AddCart
{
    public function __construct(private CartRepositoryInterface $cartRepository)
    {
    }

    public function execute(int $userId): Cart
    {
        $userIdValueObject = IntValueObject::fromInt($userId);
        $existingCart = $this->cartRepository->findByUserId($userIdValueObject);

        if ($existingCart !== null) {
            return $existingCart;
        }

        $cart = Cart::load(null, $userIdValueObject);
        $this->cartRepository->create($cart);
        $createdCart = $this->cartRepository->findByUserId($userIdValueObject);

        if ($createdCart === null) {
            throw new \Exception('Cart not created');
        }

        return $createdCart;
    }
}