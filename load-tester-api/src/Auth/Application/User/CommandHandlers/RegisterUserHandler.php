<?php
namespace App\Auth\Application\Handler;

use App\Auth\Domain\User\Entity\User;
use App\Auth\Domain\User\ValueObject\Email;
use App\Auth\Domain\User\ValueObject\UserId;

use App\Auth\Domain\User\Event\UserRegistered;
use App\Auth\Domain\User\ValueObject\PasswordHash;
use App\Auth\Infrastructure\Security\SecurityUser;
use App\Auth\Application\Service\DomainEventDispatcher;
use App\Auth\Application\User\Command\RegisterUserCommand;
use App\Auth\Domain\User\Repository\UserRepositoryInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;



final class RegisterUserHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private DomainEventDispatcher $dispatcher,
        private UserPasswordHasherInterface $hasher,
    ) {}

    public function handle(RegisterUserCommand $command): string
    {
        $userId = UserId::generate();
        $email = new Email($command->email);
        $passwordHash = new PasswordHash($command->password);

        $user = User::register($userId, $email, $passwordHash, $command->roles);
        
        // --- Hashage sécurisé (argon2id auto) ---
       $securityUser = new SecurityUser($user);
       // Hashage avec argon2id
       $hashed = $this->hasher->hashPassword($securityUser, $command->password);
       $user->setPassword(new PasswordHash($hashed));

       $this->userRepository->save($user);

        // Send OTP via Notification BC
       foreach ($user->pullRecordedEvents() as $event) {
          // On ne dispatch que les events pour lesquels il y a un handler
          if ($event instanceof UserRegistered) {
              $this->dispatcher->dispatch($event);
          }
        }

        return $userId->value();
    }
}
