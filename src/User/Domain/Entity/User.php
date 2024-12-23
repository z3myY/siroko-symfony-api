<?php

declare(strict_types=1);

namespace App\User\Domain\Entity;

use App\Shared\Domain\AggregateRoot;
use App\Shared\Domain\ValueObject\EmailValueObject;
use App\Shared\Domain\ValueObject\IntValueObject;
use App\Shared\Domain\ValueObject\StringValueObject;
use App\Shared\Domain\ValueObject\PasswordValueObject;

/**
 * Class User
 * @package App\User\Domain\Entity
 */
final class User extends AggregateRoot
{
    public function __construct(
        private StringValueObject $userName, 
        private EmailValueObject $email, 
        private PasswordValueObject $password,
        private ?IntValueObject $id,
    )
    {
    }

    public function id(): IntValueObject
    {
        return $this->id;
    }

    public function userName(): StringValueObject
    {
        return $this->userName;
    }

    public function email(): EmailValueObject
    {
        return $this->email;
    }

    public function password(): PasswordValueObject
    {
        return $this->password;
    }

    public static function load(
        StringValueObject $userName,
        EmailValueObject $email,
        PasswordValueObject $password,
        ?IntValueObject $id
    ) : self {
        return new self(
            userName: $userName, 
            email: $email,
            password: $password,
            id: $id, 
        );
    }

    public function serialize(): array
    {
        return [
            'id' => $this->id->value(),
            'user_name' => $this->userName->value(),
            'email' => $this->email->value(),
            'password' => $this->password->value(),
        ];
    }

}