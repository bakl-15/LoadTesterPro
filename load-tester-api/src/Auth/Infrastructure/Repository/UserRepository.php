<?php

namespace App\Auth\Infrastructure\Repository;

use App\Auth\Domain\User\Entity\User;

use Doctrine\ORM\EntityManagerInterface;
use App\Auth\Domain\User\ValueObject\Email;
use App\Auth\Domain\User\ValueObject\UserId;
use App\Auth\Domain\User\Repository\UserRepositoryInterface;

final class UserRepository implements UserRepositoryInterface
{
    public function __construct(private EntityManagerInterface $em) {}

    public function save(User $user): void
    {
        $this->em->persist($user);
        $this->em->flush();
    }

    public function findById(UserId $id): ?User
    {
        return $this->em->getRepository(User::class)->find($id);
    }

    public function findByEmail(Email $email): ?User
    {
        return $this->em->getRepository(User::class)->findOneBy(['email' => $email]);
    }
    public function find(UserId $id): ?User
    {
       return null;
    }
}
