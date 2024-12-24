<?php

declare(strict_types=1);

namespace App\User\Application\Create;

use App\User\Domain\Entity\User;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\Shared\Domain\ValueObject\StringValueObject;
use App\Shared\Domain\ValueObject\EmailValueObject;
use App\Shared\Domain\ValueObject\PasswordValueObject;

/**
 * Class CreateUser
 * @package App\User\Application\Create
 */
final class CreateUser
{
    public function __construct(private UserRepositoryInterface $userRepositoryInterface)
    {
    }

    public function execute(string $userName, string $email, string $password): void
    {
        $userName = StringValueObject::fromString($userName);
        $email = EmailValueObject::fromString($email);
        $password = PasswordValueObject::fromString($password);

        $user = User::load($userName, $email, $password, null);

        $this->userRepositoryInterface->create($user);
    }
}