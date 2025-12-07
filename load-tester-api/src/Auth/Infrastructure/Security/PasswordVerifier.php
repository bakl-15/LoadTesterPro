<?php
namespace App\Auth\Infrastructure\Security;

use App\Auth\Domain\User\ValueObject\PasswordHash;

class PasswordVerifier
{
    public function isValid(string $plain, PasswordHash $hash): bool
    {
        return password_verify($plain, $hash->value());
    }
}
