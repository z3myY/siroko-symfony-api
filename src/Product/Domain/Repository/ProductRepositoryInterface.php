<?php

declare(strict_types=1);

namespace App\Product\Domain\Repository;

use App\Product\Domain\Entity\Product;
use App\Shared\Domain\ValueObject\IntValueObject;

interface ProductRepositoryInterface
{
    public function findById(IntValueObject $id): ?Product;
    
    public function list(): array;

    public function updateStock(Product $product): void;
}