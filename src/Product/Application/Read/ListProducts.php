<?php

declare(strict_types=1);

namespace App\Product\Application\Read;

use App\Product\Domain\Repository\ProductRepositoryInterface;

class ListProducts
{
    public function __construct(private ProductRepositoryInterface $productRepository)
    {
    }

    public function execute(): array
    {
        return $this->productRepository->list();
    }
}