<?php

namespace App\Auth\Application\User\CommandHandlers;

use App\Auth\Application\User\Command\LoginUserCommand;
use App\Auth\Domain\User\Exception\InvalidCredentialsException;
use App\Auth\Domain\User\Repository\UserRepositoryInterface;
use App\Auth\Infrastructure\Security\SecurityUser;
use App\Auth\Infrastructure\Security\UserProvider;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

final class LoginUserHandler
{
    public function __construct(
        private UserProvider $userProvider,
        private UserPasswordHasherInterface $passwordHasher,
        private JWTTokenManagerInterface $jwtManager
    ) {}

    public function handle(LoginUserCommand $command): string
    {
        // Charge l'utilisateur via le UserProvider Symfony
        $user = $this->userProvider->loadUserByIdentifier($command->email);

        if (!$user instanceof SecurityUser) {
            throw new InvalidCredentialsException('Invalid user.');
        }

        // Vérifie le mot de passe
        if (!$this->passwordHasher->isPasswordValid($user, $command->password)) {
            throw new InvalidCredentialsException('Invalid credentials.');
        }

        // Vérifie que l'utilisateur est bien vérifié
        if (!$user->domainUser()->isVerified()) {
            throw new InvalidCredentialsException('User not verified.');
        }

        // Génère le token JWT
        return $this->jwtManager->create($user);
    }
}
