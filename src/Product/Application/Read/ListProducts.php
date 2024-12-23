<?php

declare(strict_types=1);

namespace App\Product\Application\Read;

use App\Product\Domain\Entity\Product;
use App\Product\Domain\Repository\ProductRepositoryInterface;

final class ListProducts
{
    public function __construct(private ProductRepositoryInterface $productRepository)
    {
    }

    public function execute(): array
    {
        return $this->productRepository->list();
    }
}