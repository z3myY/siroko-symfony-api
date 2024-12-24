<?php
declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

class ArrayValueObject implements ValueObjectInterface
{
    protected array $value;

    protected final function __construct(array $value)
    {
        $this->value = $value;
    }

    public static function fromArray(array $values): static
    {
        return new static($values);
    }

    public function value(): array
    {
        return $this->value;
    }

    public function jsonSerialize(): mixed
    {
        return $this->value;
    }

    public function isEmpty(): bool
    {
        return count($this->value) === 0;
    }
}
