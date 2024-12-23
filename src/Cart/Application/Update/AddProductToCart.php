<?php

declare(strict_types=1);

namespace App\Cart\Application\Update;

use App\Cart\Domain\Entity\CartProduct;
use App\Cart\Domain\Repository\CartRepositoryInterface;
use App\Product\Domain\Service\DecrementProductStockService;
use App\Product\Domain\Service\ProductStockService;

final class AddProductToCart
{
    public function __construct(
        private CartRepositoryInterface $cartRepository,
        private ProductStockService $productStockService,
        private DecrementProductStockService $decrementProductStockService
        )
    {
    }

    public function execute(CartProduct $cartProduct): void
    {
        $cart = $this->cartRepository->findById($cartProduct->cartId());

        if (!$cart) {
            throw new \Exception('Cart not found');
        }

        if (!$this->productStockService->checkStock($cartProduct->productId(), $cartProduct->quantity())) {
            throw new \Exception('Product out of stock');
        }

        $this->decrementProductStockService->execute($cartProduct->productId(), $cartProduct->quantity());

        $updateCartProduct = $cart->addProduct($cartProduct);
        $this->cartRepository->saveProduct($updateCartProduct);
    }
}