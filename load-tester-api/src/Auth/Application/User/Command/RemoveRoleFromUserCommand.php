<?php

namespace App\Auth\Application\User\Command;

class RemoveRoleFromUserCommand
{
    public function __construct(
        public string $userId,
        public string $roleName
    ) {}
}
