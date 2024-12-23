<?php

namespace App\Shared\Domain\ValueObject;

use InvalidArgumentException;

class PasswordValueObject
{
    private string $password;

    public function __construct(string $password)
    {
        $this->ensureIsValidPassword($password);
        $this->password = $password;
    }

    private function ensureIsValidPassword(string $password): void
    {
        if (strlen($password) <= 8) {
            throw new InvalidArgumentException('Password must be longer than 8 characters.');
        }

        if (!preg_match('/[A-Z]/', $password)) {
            throw new InvalidArgumentException('Password must contain at least one uppercase letter.');
        }

        if (!preg_match('/[0-9]/', $password)) {
            throw new InvalidArgumentException('Password must contain at least one number.');
        }
    }

    public static function fromString(string $password): self
    {
        return new self($password);
    }

    public function value(): string
    {
        return $this->password;
    }

    public function __toString(): string
    {
        return $this->password;
    }
}