<?php
namespace App\Auth\Domain\User\Repository;


use App\Auth\Domain\User\Entity\User;
use App\Auth\Domain\User\ValueObject\UserId;
use App\Auth\Domain\User\ValueObject\Email;


interface UserRepositoryInterface
{
     public function save(User $user): void;
     public function findByEmail(Email $email): ?User;
     public function find(UserId $id): ?User;
}