<?php

namespace App\Cart\Application\Update;

use App\Cart\Domain\Repository\CartRepositoryInterface;
use App\Product\Domain\Service\DecrementProductStockService;
use App\Product\Domain\Service\IncrementProductStockService;
use App\Shared\Domain\ValueObject\FloatValueObject;
use App\Shared\Domain\ValueObject\IntValueObject;
use App\Shared\Domain\ValueObject\StringValueObject;

class UpdateCartProduct
{

    public function __construct(
        private CartRepositoryInterface $cartRepository,
        private IncrementProductStockService $incrementProductStockService
        )
    {
    }

    public function execute(IntValueObject $cartId, IntValueObject $productId, IntValueObject $newQuantity, StringValueObject $name, FloatValueObject $price): void
    {
        $cart = $this->cartRepository->findById($cartId);

        if (!$cart) {
            throw new \Exception('Cart not found');
        }

        $cartProduct = $cart->getProduct($productId);

        if (!$cartProduct) {
            throw new \Exception('Product not found in cart');
        }
        
        $newStock = $cartProduct->quantity()->value() - $newQuantity->value();

        $this->incrementProductStockService->execute($productId, IntValueObject::fromInt($newStock));

        $cartProduct->update($newQuantity, $name, $price);
        $this->cartRepository->saveProduct($cartProduct);
    }
}