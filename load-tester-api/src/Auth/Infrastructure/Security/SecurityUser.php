<?php

namespace App\Auth\Infrastructure\Security;

use App\Auth\Domain\User\Entity\User;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final class SecurityUser implements UserInterface, PasswordAuthenticatedUserInterface
{
    public function __construct(private User $user) {}

    public function getPassword(): ?string
    {
        return $this->user->passwordHash()?->value();
    }

    public function getUserIdentifier(): string
    {
        return $this->user->email()->value();
    }

    public function getRoles(): array
    {
        return $this->user->roles();
    }

    public function eraseCredentials(): void {}

    /** 
     * Accès au domaine 
     */
    public function domainUser(): User
    {
        return $this->user;
    }
}
