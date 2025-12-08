<?php

namespace App\Auth\Application\User\CommandHandlers;

use App\Auth\Application\User\Command\RemoveRoleFromUserCommand;
use App\Auth\Domain\User\Repository\UserRepositoryInterface;
use App\Auth\Domain\User\ValueObject\UserId;

class RemoveRoleFromUserHandler
{
    public function __construct(
        private UserRepositoryInterface $users
    ) {}

    public function __invoke(RemoveRoleFromUserCommand $cmd): void
    {
        $user = $this->users->find(new UserId($cmd->userId));
        if (!$user) {
            throw new \Exception("User not found");
        }

        $user->removeRole($cmd->roleName);

        $this->users->save($user);
    }
}
