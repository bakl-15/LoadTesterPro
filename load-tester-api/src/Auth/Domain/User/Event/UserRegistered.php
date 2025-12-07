<?php
// src/Auth/Domain/User/Event/UserRegistered.php
namespace App\Auth\Domain\User\Event;

final class UserRegistered
{
    public function __construct(
        public readonly string $userId,
        public readonly string $email,
        public readonly int $otp
    ) {}
}
