<?php

declare(strict_types=1);

namespace App\User\Application\Read;

use App\User\Domain\Entity\User;
use App\User\Domain\Repository\UserRepositoryInterface;

/**
 * Class GetUsers
 * @package App\User\Application\Read
 */
final class GetUsers
{
    public function __construct(private UserRepositoryInterface $userRepository)
    {
    }

    public function execute(): array
    {
        /** @var User[] */
        $users = $this->userRepository->findAll();

        if (!$users) {
            throw new \Exception('Users not found');
        }
        return array_map(fn(User $user) => $user->serialize(), $users);
    }
}