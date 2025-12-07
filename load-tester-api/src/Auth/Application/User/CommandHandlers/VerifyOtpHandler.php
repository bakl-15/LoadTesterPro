<?php
namespace App\Auth\Application\Handler;

use App\Auth\Domain\User\Event\UserVerified;
use App\Auth\Application\Command\VerifyOtpCommand;
use App\Auth\Application\Service\DomainEventDispatcher;
use App\Auth\Domain\User\Repository\UserRepositoryInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

final class VerifyOtpHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private DomainEventDispatcher $dispatcher
    ) {}

    public function handle(VerifyOtpCommand $command): void
    {
        $user = $this->userRepository->findById($command->userId);

        if (!$user) {
            throw new \DomainException("User not found");
        }

        $user->verifyOtp($command->otp);

        $this->userRepository->save($user);
          // Send OTP via Notification BC
       foreach ($user->pullRecordedEvents() as $event) {
          // On ne dispatch que les events pour lesquels il y a un handler
          if ($event instanceof UserVerified) {
              $this->dispatcher->dispatch($event);
          }
        }


    }
}
