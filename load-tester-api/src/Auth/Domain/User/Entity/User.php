<?php
 namespace App\Auth\Domain\User\Entity;
use App\Auth\Domain\User\ValueObject\Email;
use App\Auth\Domain\User\Event\UserLoggedIn;
use App\Auth\Domain\User\ValueObject\UserId;
use App\Auth\Domain\User\Event\UserRegistered;
use App\Auth\Domain\User\Event\UserEmailVerified;
use App\Auth\Domain\User\ValueObject\PasswordHash;
use App\Auth\Domain\User\Event\UserPasswordChanged;
use App\Auth\Domain\User\Exception\InvalidPasswordException;


final class User
{
            private UserId $id;
            private Email $email;   
            private PasswordHash $passwordHash;
            private bool $isVerified = false;
            private array $recordedEvents = [];
            
            
            private function __construct(UserId $id, Email $email, PasswordHash $passwordHash)
            {
                $this->id = $id;
                $this->email = $email;
                $this->passwordHash = $passwordHash;
            }
            
            
            public static function register(UserId $id, Email $email, PasswordHash $passwordHash): self
            {
                $user = new self($id, $email, $passwordHash);
                $user->recordEvent(new UserRegistered($id, $email, new \DateTimeImmutable()));
                return $user;
            }
            
            
            public function verifyEmail(): void
            {
                if ($this->isVerified) return;
                $this->isVerified = true;
                $this->recordEvent(new UserEmailVerified($this->id, $this->email, new \DateTimeImmutable()));
            }
            
            
            public function login(string $plainPassword, \Closure $verifyPassword): void
            {
                 if (!$verifyPassword($this->passwordHash, $plainPassword)) {
                    throw new InvalidPasswordException();
                 }
                 $this->recordEvent(new UserLoggedIn($this->id, new \DateTimeImmutable()));
            }
            
            
            public function changePassword(PasswordHash $newHash): void
            {
               $this->passwordHash = $newHash;
               $this->recordEvent(new UserPasswordChanged($this->id, new \DateTimeImmutable()));
            }
            
            
            public function pullRecordedEvents(): array
            {
            $events = $this->recordedEvents;
            $this->recordedEvents = [];
            return $events;
            }
            
            
            private function recordEvent(object $event): void
            {
                $this->recordedEvents[] = $event;
            }
            
            
            public function id(): UserId { return $this->id; }
            public function email(): Email { return $this->email; }
            public function passwordHash(): PasswordHash { return $this->passwordHash; }
            public function isVerified(): bool { return $this->isVerified; }
}