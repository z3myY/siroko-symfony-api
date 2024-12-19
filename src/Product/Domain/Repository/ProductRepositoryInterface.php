<?php

declare(strict_types=1);

namespace App\Product\Domain\Repository;

use App\Product\Domain\Entity\Product;

interface ProductRepositoryInterface
{
    public function findById(int $id): ?Product;
    
    public function list(): array;
}