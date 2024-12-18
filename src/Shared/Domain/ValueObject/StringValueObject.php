<?php

namespace App\Shared\Domain\ValueObject;

use Stringable;

/** @phpstan-consistent-constructor **/
class StringValueObject implements ValueObjectInterface, Stringable
{
    protected string $value;

    public final function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function fromString(string $value): static
    {
        return new static($value);
    }

    public static function fromNullable(?string $value): static|null
    {
        return $value !== null ? new static($value) : null;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equalsTo(self $otherStringValueObject): bool
    {
        return $otherStringValueObject->value() === $this->value;
    }

    public function jsonSerialize(): string
    {
        return $this->value;
    }

    public function __toString()
    {
        return $this->value();
    }
}
