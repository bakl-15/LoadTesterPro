<?php
namespace App\Auth\Application\User\Command;
class RegisterUserCommand
{
    public function __construct(
        public string $email,
        public string $password,
        /** @var string[] $roles */
        public array $roles = []
    ) {}
}