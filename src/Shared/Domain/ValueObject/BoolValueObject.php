<?php

namespace App\Shared\Domain\ValueObject;

class BoolValueObject implements ValueObjectInterface
{
    protected bool $value;

    protected final function __construct(int $value)
    {
        $this->value = $value;
    }

    public static function true(): static
    {
        return new static(true);
    }

    public static function false(): static
    {
        return new static(false);
    }

    public function isTrue(): bool
    {
        return $this->equalsTo(self::true());
    }

    public function isFalse(): bool
    {
        return $this->equalsTo(self::false());
    }

    public static function fromBool(bool $value): static
    {
        return new static($value);
    }

    public function value(): bool
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return boolval($this->value);
    }

    public function equalsTo(self $otherIntValueObject): bool
    {
        return $otherIntValueObject->value === $this->value;
    }

    public function jsonSerialize(): bool
    {
        return $this->value;
    }
}
