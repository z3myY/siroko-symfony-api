<?php

namespace App\Product\Infrastructure\Repository;

use App\Product\Domain\Repository\ProductRepositoryInterface;
use App\Product\Domain\Entity\Product;
use App\Shared\Domain\ValueObject\FloatValueObject;
use App\Shared\Domain\ValueObject\IntValueObject;
use App\Shared\Domain\ValueObject\StringValueObject;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class ProductRepository
 * @package App\Product\Infrastructure\Repository
 */
class ProductRepository implements ProductRepositoryInterface
{

    public function __construct(
        private EntityManagerInterface $entityManager,
        private Connection $connection)
    {
    }

    public function findById(int $id): ?Product
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder
            ->select('*')
            ->from('products')
            ->where('id = :id')
            ->setParameter('id', $id);
        
        $result = $queryBuilder->executeQuery()->fetchAssociative();

        if (!$result) {
            return null;
        }

        return $this->hydrateResult($result);
    }

    public function findBySku(string $sku): ?Product
    {
        return $this->entityManager->getRepository(Product::class)->findOneBy(['sku' => $sku]);
    }

    public function save(Product $product): void
    {
        $this->entityManager->persist($product);
        $this->entityManager->flush();
    }

    public function delete(Product $product): void
    {
        $this->entityManager->remove($product);
        $this->entityManager->flush();
    }

    public function list(): array
    {
        $products = $this->entityManager->getRepository(Product::class)->findAll();
        return array_map(fn(Product $product) => $product->serialize(), $products);
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