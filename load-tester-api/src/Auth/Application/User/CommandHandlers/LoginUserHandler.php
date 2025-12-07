<?php

namespace App\Auth\Application\Command;

use App\Auth\Domain\User\Entity\User;
use App\Auth\Application\User\Command\LoginUserCommand;
use App\Auth\Domain\User\Repository\UserRepositoryInterface;
use App\Auth\Domain\User\Exception\InvalidCredentialsException;
use App\Auth\Domain\User\ValueObject\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

final class LoginUserHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepo,
        private UserPasswordHasherInterface $passwordHasher,
        private JWTTokenManagerInterface $jwtManager
    ) {}

    public function handle(LoginUserCommand $command): string
    {
        $user = $this->userRepo->findByEmail(new Email($command->email));

        if (null === $user || !$user instanceof User) {
            throw new InvalidCredentialsException('Invalid credentials.');
        }

        if (!$this->passwordHasher->isPasswordValid($user, $command->password)) {
            throw new InvalidCredentialsException('Invalid credentials.');
        }

        if (!$user->isVerified()) {
            throw new InvalidCredentialsException('User not verified.');
        }

        return $this->jwtManager->create($user); // Retourne le token JWT
    }
}

