<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

/**
 * Class Price
 * @package App\Shared\Domain\ValueObject
 */
class Price extends FloatValueObject
{
    public static function fromString(string $value): static
    {
        return new static($value);
    }

    public static function fromNullable(?float $value): ?static
    {
        return $value !== null ? new static($value) : null;
    }
}