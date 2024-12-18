<?php

declare(strict_types=1);

namespace App\Product\Domain\Repository;

use App\Product\Domain\Entity\Product;

interface ProductRepositoryInterface
{
    public function findById(int $id): ?Product;

    public function findBySku(string $sku): ?Product;
    
    public function list(): array;
    
    public function save(Product $product): void;
    
    public function delete(Product $product): void;
}