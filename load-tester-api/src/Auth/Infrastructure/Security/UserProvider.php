<?php

namespace App\Auth\Infrastructure\Security;

use App\Auth\Domain\User\Repository\UserRepositoryInterface;
use App\Auth\Domain\User\ValueObject\Email;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;

final class UserProvider implements UserProviderInterface
{
    public function __construct(private UserRepositoryInterface $userRepo) {}

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        $email = new Email($identifier);
        $user = $this->userRepo->findByEmail($email);

        if (!$user) {
            throw new UserNotFoundException(sprintf('User "%s" not found.', $identifier));
        }

        return new SecurityUser($user);
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        if (!$user instanceof SecurityUser) {
            throw new \InvalidArgumentException('Invalid user class');
        }

        $domainUser = $user->domainUser();

        return $this->loadUserByIdentifier($domainUser->email()->value());
    }

    public function supportsClass(string $class): bool
    {
        return $class === SecurityUser::class;
    }
}
