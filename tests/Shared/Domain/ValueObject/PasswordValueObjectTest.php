<?php

declare(strict_types=1);

namespace Test\Shared\Domain\ValueObject;

use App\Shared\Domain\ValueObject\PasswordValueObject;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * Class PasswordValueObjectTest
 * @package Test\Shared\Domain\ValueObject
 */
class PasswordValueObjectTest extends TestCase
{
    public function testValidPassword(): void
    {
        $password = new PasswordValueObject('ValidPass123');
        $this->assertEquals('ValidPass123', (string) $password);
    }

    public function testPasswordTooShort(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Password must be longer than 8 characters.');
        new PasswordValueObject('Short1');
    }

    public function testPasswordWithoutUppercase(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Password must contain at least one uppercase letter.');
        new PasswordValueObject('nouppercase1');
    }

    public function testPasswordWithoutNumber(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Password must contain at least one number.');
        new PasswordValueObject('NoNumberPass');
    }
}