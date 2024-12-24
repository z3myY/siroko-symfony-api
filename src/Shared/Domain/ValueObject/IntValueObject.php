<?php

namespace App\Shared\Domain\ValueObject;

class IntValueObject implements ValueObjectInterface
{
    protected int $value;

    private final function __construct(int $value)
    {
        $this->value = $value;
    }

    public static function fromInt(int $value): static
    {
        return new static($value);
    }

    public static function fromString(string $value): static
    {
        return new static((int)$value);
    }

    public static function fromNullable(?int $value): static|null
    {
        return $value !== null ? new static($value) : null;
    }

    public function value(): int
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return (string)$this->value;
    }

    public function equalsTo(self $otherIntValueObject): bool
    {
        return $otherIntValueObject->value === $this->value;
    }

    public function increment(): self
    {
        return new self($this->value + 1);
    }

    public function decrement(): self
    {
        return new self($this->value - 1);
    }

    public function incrementBy(int $quantity): self
    {
        $this->value += $quantity;
        return $this;
    }

    public function decrementBy(int $quantity): self
    {
        $this->value -= $quantity;
        return $this;
    }

    public function multiplyBy(int $quantity): self
    {
        $this->value *= $quantity;
        return $this;
    }

    public function jsonSerialize(): int
    {
        return $this->value;
    }
}
