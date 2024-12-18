<?php

namespace App\Shared\Domain\ValueObject;

class EmailValueObject extends StringValueObject
{
    public static function fromString(string $value): static
    {
        if (! filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException(sprintf('Invalid email address: %s', $value));
        }

        return new static($value);
    }
}
