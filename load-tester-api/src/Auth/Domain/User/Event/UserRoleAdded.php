<?php
// src/Auth/Domain/User/Event/UserRoleAdded.php
namespace App\Auth\Domain\User\Event;

use App\Auth\Domain\User\ValueObject\UserId;
use App\Auth\Domain\Role\ValueObject\Permission;
use App\Auth\Domain\Role\Entity\Role;

final class UserRoleAdded
{
    public function __construct(
        public readonly UserId $userId,
        public readonly string $roleName,
        public readonly \DateTimeImmutable $occurredAt = new \DateTimeImmutable()
    ) {}
}
