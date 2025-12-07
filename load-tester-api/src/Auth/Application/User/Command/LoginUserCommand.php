<?php

namespace App\Auth\Application\User\Command;

class LoginUserCommand
{
    public function __construct(
        public string $email,
        public string $password
    ) {}
}
