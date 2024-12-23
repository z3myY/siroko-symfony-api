<?php

declare(strict_types=1);

namespace App\Cart\Application\Read;

use App\Cart\Domain\Repository\CartRepositoryInterface;
use App\Shared\Domain\ValueObject\IntValueObject;

class GetCartProducts
{
    public function __construct(private CartRepositoryInterface $cartRepository)
    {
    }

    public function execute(int $cartId): array
    {
        $cart = $this->cartRepository->findById(IntValueObject::fromInt($cartId));
        $products = [];

        foreach ($cart->products() as $product) {
            $products[] = $product->serialize();
        }
        
        return $products;
    }
}