<?php
namespace App\Auth\Domain\User\Event;


use App\Auth\Domain\User\ValueObject\UserId;
use App\Auth\Domain\User\ValueObject\Email;


final class UserEmailVerified
{
    public function __construct(
        public readonly UserId $id, 
        public readonly Email $email,
        public readonly \DateTimeImmutable $verifiedAt
    ) {}
}