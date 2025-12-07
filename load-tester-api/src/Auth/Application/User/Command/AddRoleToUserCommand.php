<?php
namespace App\Auth\Application\User\Command;

class AddRoleToUserCommand
{
    public function __construct(
        public string $userId,
        public string $roleName
    ) {}
}