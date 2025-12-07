<?php
// src/Auth/Domain/User/Event/UserVerified.php
namespace App\Auth\Domain\User\Event;

final class UserVerified
{
    public function __construct(public readonly string $userId) {}
}
