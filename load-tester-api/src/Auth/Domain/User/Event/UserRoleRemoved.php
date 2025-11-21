<?php
// src/Auth/Domain/User/Event/UserRoleRemoved.php
namespace App\Auth\Domain\User\Event;

use App\Auth\Domain\User\ValueObject\UserId;

final class UserRoleRemoved
{
    public function __construct(
        public readonly UserId $userId,
        public readonly string $roleName,
        public readonly \DateTimeImmutable $occurredAt = new \DateTimeImmutable()
    ) {}
}
