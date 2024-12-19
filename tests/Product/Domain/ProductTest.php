<?php 

declare(strict_types=1);

namespace Test\Product\Domain\Entity;

use PHPUnit\Framework\TestCase;
use App\Shared\Domain\ValueObject\FloatValueObject;
use App\Shared\Domain\ValueObject\IntValueObject;
use App\Shared\Domain\ValueObject\StringValueObject;
use App\Product\Domain\Entity\Product;

/**
 * Class ProductTest
 * @package Test\Product\Domain\Entity
 */
class ProductTest extends TestCase
{
    public function testProductCreation(): void
    {
        $id = IntValueObject::fromInt(1);
        $name = StringValueObject::fromString('Test Product');
        $description = StringValueObject::fromString('This is a test product.');
        $price = FloatValueObject::from(99.99);
        $stock = IntValueObject::fromInt(10);
        $imageUrl = StringValueObject::fromString('http://example.com/image.jpg');
        $category = StringValueObject::fromString('Test Category');
        $sku = StringValueObject::fromString('TESTSKU');
        $availability = StringValueObject::fromString('In Stock');
        $discount = FloatValueObject::from(10.0);
        $brand = StringValueObject::fromString('Test Brand');
        $rating = FloatValueObject::from(4.5);
        $reviews = IntValueObject::fromInt(100);

        $product = new Product(
            $id,
            $name,
            $description,
            $price,
            $stock,
            $category,
            $sku,
            $imageUrl,
            $availability,
            $discount,
            $brand,
            $rating,
            $reviews
        );

        $this->assertSame($id, $product->id());
        $this->assertSame($name, $product->name());
        $this->assertSame($description, $product->description());
        $this->assertSame($price, $product->price());
        $this->assertSame($stock, $product->stock());
        $this->assertSame($imageUrl, $product->imageUrl());
        $this->assertSame($category, $product->category());
        $this->assertSame($sku, $product->sku());
        $this->assertSame($availability, $product->availability());
        $this->assertSame($discount, $product->discount());
        $this->assertSame($brand, $product->brand());
        $this->assertSame($rating, $product->rating());
        $this->assertSame($reviews, $product->reviews());
    }

    public function testProductSerialization(): void
    {
        $id = IntValueObject::fromInt(1);
        $name = StringValueObject::fromString('Test Product');
        $description = StringValueObject::fromString('This is a test product.');
        $price = FloatValueObject::from(99.99);
        $stock = IntValueObject::fromInt(10);
        $imageUrl = StringValueObject::fromString('http://example.com/image.jpg');
        $category = StringValueObject::fromString('Test Category');
        $sku = StringValueObject::fromString('TESTSKU');
        $availability = StringValueObject::fromString('In Stock');
        $discount = FloatValueObject::from(10.0);
        $brand = StringValueObject::fromString('Test Brand');
        $rating = FloatValueObject::from(4.5);
        $reviews = IntValueObject::fromInt(100);

        $product = new Product(
            $id,
            $name,
            $description,
            $price,
            $stock,
            $category,
            $sku,
            $imageUrl,
            $availability,
            $discount,
            $brand,
            $rating,
            $reviews
        );

        $expected = [
            'id' => 1,
            'name' => 'Test Product',
            'description' => 'This is a test product.',
            'price' => 99.99,
            'stock' => 10,
            'category' => 'Test Category',
            'sku' => 'TESTSKU',
            'imageUrl' => 'http://example.com/image.jpg',
            'availability' => 'In Stock',
            'discount' => 10.0,
            'brand' => 'Test Brand',
            'rating' => 4.5,
            'reviews' => 100,
        ];

        $this->assertSame($expected, $product->serialize());
    }

    public function testProductLoad(): void
    {
        $id = IntValueObject::fromInt(1);
        $name = StringValueObject::fromString('Test Product');
        $description = StringValueObject::fromString('This is a test product.');
        $price = FloatValueObject::from(99.99);
        $stock = IntValueObject::fromInt(10);
        $imageUrl = StringValueObject::fromString('http://example.com/image.jpg');
        $category = StringValueObject::fromString('Test Category');
        $sku = StringValueObject::fromString('TESTSKU');
        $availability = StringValueObject::fromString('In Stock');
        $discount = FloatValueObject::from(10.0);
        $brand = StringValueObject::fromString('Test Brand');
        $rating = FloatValueObject::from(4.5);
        $reviews = IntValueObject::fromInt(100);

        $product = Product::load(
            $id,
            $name,
            $description,
            $price,
            $stock,
            $category,
            $sku,
            $imageUrl,
            $availability,
            $discount,
            $brand,
            $rating,
            $reviews
        );

        $this->assertSame($id, $product->id());
        $this->assertSame($name, $product->name());
        $this->assertSame($description, $product->description());
        $this->assertSame($price, $product->price());
        $this->assertSame($stock, $product->stock());
        $this->assertSame($imageUrl, $product->imageUrl());
        $this->assertSame($category, $product->category());
        $this->assertSame($sku, $product->sku());
        $this->assertSame($availability, $product->availability());
        $this->assertSame($discount, $product->discount());
        $this->assertSame($brand, $product->brand());
        $this->assertSame($rating, $product->rating());
        $this->assertSame($reviews, $product->reviews());
    }

    public function testProductLoadWithNullValues(): void
    {
        $id = IntValueObject::fromInt(1);
        $name = StringValueObject::fromString('Test Product');
        $description = StringValueObject::fromString('This is a test product.');
        $price = FloatValueObject::from(99.99);
        $stock = IntValueObject::fromInt(10);
        $imageUrl = null;
        $category = StringValueObject::fromString('Test Category');
        $sku = StringValueObject::fromString('TESTSKU');
        $availability = null;
        $discount = null;
        $brand = null;
        $rating = null;
        $reviews = null;

        $product = Product::load(
            $id,
            $name,
            $description,
            $price,
            $stock,
            $category,
            $sku,
            $imageUrl,
            $availability,
            $discount,
            $brand,
            $rating,
            $reviews
        );

        $this->assertSame($id, $product->id());
        $this->assertSame($name, $product->name());
        $this->assertSame($description, $product->description());
        $this->assertSame($price, $product->price());
        $this->assertSame($stock, $product->stock());
        $this->assertNull($product->imageUrl());
        $this->assertSame($category, $product->category());
        $this->assertSame($sku, $product->sku());
        $this->assertNull($product->availability());
        $this->assertNull($product->discount());
        $this->assertNull($product->brand());
        $this->assertNull($product->rating());
        $this->assertNull($product->reviews());
    }

    public function testProductLoadWithPartialValues(): void
    {
        $id = IntValueObject::fromInt(1);
        $name = StringValueObject::fromString('Test Product');
        $description = StringValueObject::fromString('This is a test product.');
        $price = FloatValueObject::from(99.99);
        $stock = IntValueObject::fromInt(10);
        $imageUrl = null;
        $category = StringValueObject::fromString('Test Category');
        $sku = StringValueObject::fromString('TESTSKU');
        $availability = null;
        $discount = null;
        $brand = null;
        $rating = null;
        $reviews = null;

        $product = Product::load(
            $id,
            $name,
            $description,
            $price,
            $stock,
            $category,
            $sku,
            $imageUrl,
            $availability,
            $discount,
            $brand,
            $rating,
            $reviews
        );

        $this->assertSame($id, $product->id());
        $this->assertSame($name, $product->name());
        $this->assertSame($description, $product->description());
        $this->assertSame($price, $product->price());
        $this->assertSame($stock, $product->stock());
        $this->assertNull($product->imageUrl());
        $this->assertSame($category, $product->category());
        $this->assertSame($sku, $product->sku());
        $this->assertNull($product->availability());
        $this->assertNull($product->discount());
        $this->assertNull($product->brand());
        $this->assertNull($product->rating());
        $this->assertNull($product->reviews());
    }

    public function testProductLoadWithEmptyValues(): void
    {
        $id = IntValueObject::fromInt(1);
        $name = StringValueObject::fromString('Test Product');
        $description = StringValueObject::fromString('This is a test product.');
        $price = FloatValueObject::from(99.99);
        $stock = IntValueObject::fromInt(10);
        $imageUrl = StringValueObject::fromString('');
        $category = StringValueObject::fromString('Test Category');
        $sku = StringValueObject::fromString('TESTSKU');
        $availability = StringValueObject::fromString('');
        $discount = FloatValueObject::from(0.0);
        $brand = StringValueObject::fromString('');
        $rating = FloatValueObject::from(0.0);
        $reviews = IntValueObject::fromInt(0);

        $product = Product::load(
            $id,
            $name,
            $description,
            $price,
            $stock,
            $category,
            $sku,
            $imageUrl,
            $availability,
            $discount,
            $brand,
            $rating,
            $reviews
        );

        $this->assertSame($id, $product->id());
        $this->assertSame($name, $product->name());
        $this->assertSame($description, $product->description());
        $this->assertSame($price, $product->price());
        $this->assertSame($stock, $product->stock());
        $this->assertNull($product->imageUrl());
        $this->assertSame($category, $product->category());
        $this->assertSame($sku, $product->sku());
        $this->assertNull($product->availability());
        $this->assertSame($discount, $product->discount());
        $this->assertNull($product->brand());
        $this->assertSame($rating, $product->rating());
        $this->assertSame($reviews, $product->reviews());
    }

    public function testProductLoadWithStringAndEmptyValues(): void
    {
        $id = IntValueObject::fromString('1');
        $name = StringValueObject::fromString('Test Product');
        $description = StringValueObject::fromString('This is a test product.');
        $price = FloatValueObject::fromString('99.99');
        $stock = IntValueObject::fromString('10');
        $imageUrl = StringValueObject::fromString('');
        $category = StringValueObject::fromString('Test Category');
        $sku = StringValueObject::fromString('TESTSKU');
        $availability = StringValueObject::fromString('');
        $discount = FloatValueObject::fromString('10.0');
        $brand = StringValueObject::fromString('');
        $rating = FloatValueObject::fromString('4.5');
        $reviews = IntValueObject::fromString('120');

        $product = Product::load(
            $id,
            $name,
            $description,
            $price,
            $stock,
            $category,
            $sku,
            $imageUrl,
            $availability,
            $discount,
            $brand,
            $rating,
            $reviews
        );

        $this->assertSame(1, $product->id()->value());
        $this->assertSame('Test Product', $product->name()->value());
        $this->assertSame('This is a test product.', $product->description()->value());
        $this->assertSame(99.99, $product->price()->value());
        $this->assertSame(10, $product->stock()->value());
        $this->assertNull($product->imageUrl());
        $this->assertSame('Test Category', $product->category()->value());
        $this->assertSame('TESTSKU', $product->sku()->value());
        $this->assertNull($product->availability());
        $this->assertSame(10.0, $product->discount()->value());
        $this->assertNull($product->brand());
        $this->assertSame(4.5, $product->rating()->value());
        $this->assertSame(120, $product->reviews()->value());
    }
}