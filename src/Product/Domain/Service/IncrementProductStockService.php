<?php

declare(strict_types=1);

namespace App\Product\Domain\Service;

use App\Shared\Domain\ValueObject\IntValueObject;
use App\Product\Domain\Repository\ProductRepositoryInterface;

/**
 * IncrementProductStockService
 * @package App\Product\Domain\Service
 */
class IncrementProductStockService
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
        
        $newStock = $product->stock()->incrementBy($quantity->value());
        $product->stock($newStock);
        $this->productRepository->updateStock($product);
    }
}