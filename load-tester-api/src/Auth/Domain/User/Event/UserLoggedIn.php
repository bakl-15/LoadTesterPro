<?php
namespace App\Auth\Domain\User\Event;


use App\Auth\Domain\User\ValueObject\UserId;


final class UserLoggedIn
{
     public function __construct(public readonly UserId $id, public readonly \DateTimeImmutable $occurredAt) {}
}