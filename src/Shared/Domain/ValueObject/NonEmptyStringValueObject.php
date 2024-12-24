<?php

namespace App\Shared\Domain\ValueObject;

class NonEmptyStringValueObject extends StringValueObject
{
    public static function fromString(string $value): static
    {
        if (empty($value)) {
            throw new \InvalidArgumentException('Empty value provided');
        }

        return new static($value);
    }
}
