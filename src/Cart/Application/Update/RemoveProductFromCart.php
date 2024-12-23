<?php

declare(strict_types=1);

namespace App\Cart\Application\Update;

use App\Cart\Domain\Repository\CartProductRepositoryInterface;
use App\Cart\Domain\Repository\CartRepositoryInterface;
use App\Product\Domain\Service\IncrementProductStockService;
use App\Shared\Domain\ValueObject\IntValueObject;

/**
 * Clss RemoveProductFromCart
 * @package App\Cart\Application\Update
 */
final class RemoveProductFromCart
{
    public function __construct(
        private CartProductRepositoryInterface $cartProductRepository,
        private CartRepositoryInterface $cartRepository,
        private IncrementProductStockService $incrementProductStockService
        )
    {
    }

    public function execute(IntValueObject $cartId, IntValueObject $productId): void
    {
        $cart = $this->cartRepository->findById($cartId);
        $cartProduct = $cart->getProduct($productId);
        $cartProduct = $this->cartProductRepository->find($cartProduct);


        if ($cartProduct) {
            $this->cartProductRepository->removeProduct($cartProduct);
            $this->incrementProductStockService->execute($productId, $cartProduct->quantity());
        }
    }
}