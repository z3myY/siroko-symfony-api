<?php

namespace App\Shared\Domain\ValueObject;

use App\Shared\Domain\ValueObject\ValueObjectInterface;

class FloatValueObject implements ValueObjectInterface
{
    private float $value;

    final private function __construct(float $value)
    {
        $this->value = $value;
    }

    public static function from(float $value): static
    {
        return new static($value);
    }

    public static function fromString(string $value): static
    {
        return new static((float)$value);
    }

    public static function fromNullable(?float $value): static|null
    {
        return $value !== null ? new static($value) : null;
    }

    public function value(): float
    {
        return $this->value;
    }

    public function equalTo(FloatValueObject $other): bool
    {
        return static::class === \get_class($other) && $other->value === $this->value;
    }

    public function isBiggerThan(FloatValueObject $other): bool
    {
        return static::class === \get_class($other) && $this->value > $other->value;
    }

    public function multiplyBy(int $quantity): self
    {
        return new self($this->value * $quantity);
    }

    final public function jsonSerialize(): float
    {
        return $this->value;
    }
}
