<?php

declare(strict_types=1);

namespace App\Product\Domain\Entity;

use App\Shared\Domain\AggregateRoot;
use App\Shared\Domain\ValueObject\FloatValueObject;
use App\Shared\Domain\ValueObject\IntValueObject;
use App\Shared\Domain\ValueObject\StringValueObject;

/**
 * Class Product
 * @package App\Product\Domain\Entity
 */
final class Product extends AggregateRoot
{
    public function __construct(
        private IntValueObject $id,
        private StringValueObject $name,
        private StringValueObject $description,
        private FloatValueObject $price,
        private IntValueObject $stock,
        private StringValueObject $category,
        private StringValueObject $sku,
        private ?StringValueObject $imageUrl = null,
        private ?StringValueObject $availability = null,
        private ?FloatValueObject $discount = null,
        private ?StringValueObject $brand = null,
        private ?FloatValueObject $rating = null,
        private ?IntValueObject $reviews = null
    ) {
    }



    public function id(): IntValueObject
    {
        return $this->id;
    }

    public function name(): StringValueObject
    {
        return $this->name;
    }

    public function description(): StringValueObject
    {
        return $this->description;
    }

    public function price(): FloatValueObject
    {
        return $this->price;
    }

    public function stock(): IntValueObject
    {
        return $this->stock;
    }

    public function imageUrl(): ?StringValueObject
    {
        return $this->imageUrl && $this->imageUrl->value() !== '' ? $this->imageUrl : null;
    }

    public function category(): StringValueObject
    {
        return $this->category;
    }

    public function sku(): StringValueObject
    {
        return $this->sku;
    }

    public function availability(): ?StringValueObject
    {
        return $this->availability && $this->availability->value() !== '' ? $this->availability : null;
    }

    public function discount(): ?FloatValueObject
    {
        return $this->discount;
    }

    public function brand(): ?StringValueObject
    {
        return $this->brand && $this->brand->value() !== '' ? $this->brand : null;
    }

    public function rating(): ?FloatValueObject
    {
        return $this->rating;
    }

    public function reviews(): ?IntValueObject
    {
        return $this->reviews;
    }

    public function serialize(): array
    {
        return [
            'id' => $this->id->value(),
            'name' => $this->name->value(),
            'description' => $this->description->value(),
            'price' => $this->price->value(),
            'stock' => $this->stock->value(),
            'category' => $this->category->value(),
            'sku' => $this->sku->value(),
            'imageUrl' => $this->imageUrl && $this->imageUrl->value() !== '' ? $this->imageUrl->value() : null,
            'availability' => $this->availability && $this->availability->value() !== '' ? $this->availability->value() : null,
            'discount' => $this->discount ? $this->discount->value() : null,
            'brand' => $this->brand && $this->brand->value() !== '' ? $this->brand->value() : null,
            'rating' => $this->rating ? $this->rating->value() : null,
            'reviews' => $this->reviews ? $this->reviews->value() : null,
        ];
    }

    public static function load(
        IntValueObject $id,
        StringValueObject $name,
        StringValueObject $description,
        FloatValueObject $price,
        IntValueObject $stock,
        StringValueObject $category,
        StringValueObject $sku,
        ?StringValueObject $imageUrl,
        ?StringValueObject $availability = null,
        ?FloatValueObject $discount = null,
        ?StringValueObject $brand = null,
        ?FloatValueObject $rating = null,
        ?IntValueObject $reviews = null
    ): self {
        return new self(
            id: $id,
            name: $name,
            description: $description,
            price: $price,
            stock: $stock,
            category: $category,
            sku: $sku,
            imageUrl: $imageUrl,
            availability: $availability,
            discount: $discount,
            brand: $brand,
            rating: $rating,
            reviews: $reviews
        );
    }
}