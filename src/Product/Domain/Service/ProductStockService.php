<?php

declare(strict_types=1);

namespace App\Product\Domain\Service;

use App\Product\Domain\Repository\ProductRepositoryInterface;
use App\Shared\Domain\ValueObject\IntValueObject;

class ProductStockService
{
    public function __construct(private ProductRepositoryInterface $productRepository)
    {
    }

    public function checkStock(IntValueObject $productId, IntValueObject $quantity): bool
    {
        $product = $this->productRepository->findById($productId);

        if (!$product) {
            throw new \Exception('Product not found');
        }

        if($product->stock()->value() >= $quantity->value()){
            return true;
        }
        return false;
    }
}