<?php

declare(strict_types=1);

namespace App\Product\Application\Read;

use App\Product\Domain\Repository\ProductRepositoryInterface;
use App\Shared\Domain\ValueObject\IntValueObject;

class FindProduct
{
    public function __construct(private ProductRepositoryInterface $productRepository)
    {
    }

    public function execute(int $productId): array
    {
        $product = $this->productRepository->findById(IntValueObject::fromInt($productId));
        if ($product === null) {
            throw new \Exception('Product not found');
        }
        return $product->serialize();
    }
}