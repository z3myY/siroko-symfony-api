<?php

namespace App\Shared\Domain\ValueObject;

class DateTimeValueObject extends \DateTimeImmutable implements ValueObjectInterface
{
    private const TIME_ZONE = 'UTC';
    private const TIME_FORMAT = 'Y-m-d\TH:i:s.uP';

    protected $value = '2024-01-29';

    final private function __construct($time, $timezone)
    {
        parent::__construct($time, $timezone);
    }

    final public static function from(string $str): static
    {
        return new static($str, new \DateTimeZone(self::TIME_ZONE));
    }

    public static function fromNullable(?string $value): static|null
    {
        return $value !== null ? new static($value, new \DateTimeZone(self::TIME_ZONE)) : null;
    }

    final public static function now(): static
    {
        return static::from('now');
    }

    final public static function fromFormat(string $format, string $str): static
    {
        $dateTime = \DateTimeImmutable::createFromFormat($format, $str, new \DateTimeZone(self::TIME_ZONE));

        return static::from($dateTime->format(self::TIME_FORMAT));
    }

    final public static function fromTimestamp(int $timestamp): static
    {
        return self::fromFormat('U', (string) $timestamp);
    }

    final public function jsonSerialize(): string
    {
        return $this->value();
    }

    final public function value(): string
    {
        return $this->format(\DATE_ATOM);
    }
}
