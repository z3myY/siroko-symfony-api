<?php

namespace App\Shared\Domain\ValueObject;

class EnumValueObject extends StringValueObject
{
    private static array $allowedValues;

    public static function fromString(string $value): static
    {
        self::guard($value);        

        return parent::fromString($value);
    }

    private static function guard($value): void
    {
        if (false === self::isValid($value)) {
            throw new \InvalidArgumentException(
                \sprintf(
                    '<%s> not allowed value, allowed values: <%s> for enum class <%s>',
                    $value,
                    \implode(' ', static::allowedValues()),
                    static::class,
                ),
            );
        }
    }

    final public static function allowedValues(): array
    {
        if (!isset(self::$allowedValues[static::class])) {
            $reflection = new \ReflectionClass(static::class);
            self::$allowedValues[static::class] = $reflection->getConstants();
        }

        return self::$allowedValues[static::class];
    }

    private static function isValid($value): bool
    {
        return \in_array($value, static::allowedValues(), true);
    }
}
