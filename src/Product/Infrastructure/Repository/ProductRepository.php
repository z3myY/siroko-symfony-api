<?php

namespace App\Product\Infrastructure\Repository;

use App\Product\Domain\Repository\ProductRepositoryInterface;
use App\Product\Domain\Entity\Product;
use App\Shared\Domain\ValueObject\FloatValueObject;
use App\Shared\Domain\ValueObject\IntValueObject;
use App\Shared\Domain\ValueObject\StringValueObject;
use Doctrine\DBAL\Connection;

/**
 * Class ProductRepository
 * @package App\Product\Infrastructure\Repository
 */
class ProductRepository implements ProductRepositoryInterface
{

    public function __construct(
        private Connection $connection)
    {
    }

    public function findById(IntValueObject $id): ?Product
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder
            ->select('*')
            ->from('products')
            ->where('id = :id')
            ->setParameter('id', $id->value());
        
        $product = $queryBuilder->executeQuery()->fetchAssociative();

        if (!$product) {
            return null;
        }

        return $this->hydrateResult($product);
    }

    public function list(): array
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder
            ->select('*')
            ->from('products');
        
        $products = $queryBuilder->executeQuery()->fetchAllAssociative();

        if (!$products) {
            return [];
        }
        return array_map(fn(array $productData) => $this->hydrateResult($productData)->serialize(), $products);
    }

    public function updateStock(Product $product): void
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder
            ->update('products')
            ->set('stock', ':stock')
            ->where('id = :id')
            ->setParameter('stock', $product->stock()->value())
            ->setParameter('id', $product->id()->value());
        
        $queryBuilder->executeStatement();
    }

    function hydrateResult(array $result): Product 
    {
        return Product::load(
            IntValueObject::fromInt((int) $result['id']),
            StringValueObject::fromString($result['name']),
            StringValueObject::fromString($result['description']),
            FloatValueObject::from((float) $result['price']),
            IntValueObject::fromInt((int) $result['stock']),
            $result['image_url'] ? StringValueObject::fromString($result['image_url']) : null,
            StringValueObject::fromString($result['category']),
            StringValueObject::fromString($result['sku']),
            $result['availability'] ? StringValueObject::fromString($result['availability']) : null,
            $result['discount'] !== null ? FloatValueObject::from((float) $result['discount']) : null,
            $result['brand'] ? StringValueObject::fromString($result['brand']) : null,
            $result['rating'] !== null ? FloatValueObject::from((float) $result['rating']) : null,
            $result['reviews'] !== null ? IntValueObject::fromInt((int) $result['reviews']) : null
        );
        
    }
}