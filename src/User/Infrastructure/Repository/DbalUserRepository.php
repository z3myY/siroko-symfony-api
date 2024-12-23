<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Repository;

use App\Shared\Domain\ValueObject\EmailValueObject;
use App\Shared\Domain\ValueObject\IntValueObject;
use App\Shared\Domain\ValueObject\PasswordValueObject;
use App\Shared\Domain\ValueObject\StringValueObject;
use App\User\Domain\Entity\User;
use App\User\Domain\Repository\UserRepositoryInterface;
use Doctrine\DBAL\Connection;
use phpDocumentor\Reflection\PseudoTypes\StringValue;

/**
 * Class DbalUserRepository
 * @package App\User\Infrastructure\Repository
 */
final class DbalUserRepository implements UserRepositoryInterface
{
    public function __construct(
        private Connection $connection)
    {
    }

    public function create(User $user): void
    {
        $this->connection->insert('users', [
            'user_name' => $user->userName()->value(),
            'email' => $user->email()->value(),
            'password' => $user->password()->value(),
        ]);
    }

    /**
     * @return User[]
     */
    public function findAll(): array
    {
        $query = $this->connection->createQueryBuilder()
            ->select('*')
            ->from('users');

        $usersData = $query->executeQuery()->fetchAllAssociative();

        return array_map([$this, 'hydrateResult'], $usersData);
    }

    private function hydrateResult(array $data): User
    {
        return User::load(
            StringValueObject::fromString((string) $data['user_name']),
            EmailValueObject::fromString((string) $data['email']),
            PasswordValueObject::fromString((string) $data['password']),
            IntValueObject::fromInt((int) $data['id'])
        );
    }
}
