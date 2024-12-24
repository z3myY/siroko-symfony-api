<?php

declare(strict_types=1);

namespace App\Product\Domain\Service;

use App\Shared\Domain\ValueObject\IntValueObject;
use App\Product\Domain\Repository\ProductRepositoryInterface;

/**
 * Class DecrementProductStockService
 * @package App\Product\Domain\Service
 */
class DecrementProductStockService
{
    public function __construct(private ProductRepositoryInterface $productRepository)
    {
    }

    public function execute(IntValueObject $productId, IntValueObject $quantity): void
    {
        $product = $this->productRepository->findById($productId);

        if (!$product) {
            throw new \Exception('Product not found');
        }
        
        $product->stock()->decrementBy($quantity->value());
        $this->productRepository->updateStock($product);
    }
}