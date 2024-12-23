<?php

declare(strict_types=1);

namespace App\Product\Application\Read;

use App\Product\Domain\Entity\Product;
use App\Product\Domain\Repository\ProductRepositoryInterface;
use App\Shared\Domain\ValueObject\IntValueObject;

final class FindProduct
{
    public function __construct(private ProductRepositoryInterface $productRepository)
    {
    }

    public function execute(int $productId): array
    {
        $product = $this->productRepository->findById(IntValueObject::fromInt($productId));
        return $product->serialize();
    }
}