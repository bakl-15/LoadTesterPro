<?php
namespace App\Auth\Domain\User\Event;


use App\Auth\Domain\User\ValueObject\UserId;
use App\Auth\Domain\User\ValueObject\Email;


final class UserRegistered
{
     public function __construct(
         public readonly UserId $userId,  
         public readonly Email $email, 
         public readonly \DateTimeImmutable $occurredAt
     ) {}  public function userId(): UserId
    {
        return $this->userId;
    }

    public function email(): Email
    {
        return $this->email;
    }

}