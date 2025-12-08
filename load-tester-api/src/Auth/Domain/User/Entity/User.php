<?php
namespace App\Auth\Domain\User\Entity;

use App\Auth\Domain\User\ValueObject\Otp;
use App\Auth\Domain\User\ValueObject\Email;
use App\Auth\Domain\User\Event\UserVerified;
use App\Auth\Domain\User\ValueObject\UserId;
use App\Auth\Domain\User\Event\UserRoleAdded;
use App\Auth\Domain\User\Event\UserRegistered;
use App\Auth\Domain\User\Event\UserRoleRemoved;
use App\Auth\Domain\User\ValueObject\PasswordHash;

use App\Auth\Domain\Role\Exception\RoleNotAssignedException;
use App\Auth\Domain\User\Exception\RoleAlreadyAssignedException;

final class User
{
    private UserId $id;
    private Email $email;
    private PasswordHash $passwordHash;
    private bool $isVerified = false;

    private ?Otp $otp = null;

    /** @var string[] map roleName => roleName */
    private array $roles = [];

    private array $recordedEvents = [];

    private \DateTimeImmutable $createdAt;
    private \DateTimeImmutable $updatedAt;

    private function __construct(UserId $id, Email $email, PasswordHash $passwordHash, array $roles = [])
    {
        $this->id = $id;
        $this->email = $email;
        $this->passwordHash = $passwordHash;

        foreach ($roles as $r) {
            $this->roles[strtoupper($r)] = strtoupper($r);
        }

        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    /** ---------------- REGISTER ---------------- */

    public static function register(UserId $id, Email $email, PasswordHash $hash, array $roles = []): self
    {
        $user = new self($id, $email, $hash, $roles);

        $user->generateOtp();

        $user->recordEvent(new UserRegistered(
            userId: $id->value(),
            email: $email->value(),
            otp: $user->otp()->code()
        ));

        return $user;
    }
    /**-----------set password ----------------- */
    public function setPassword(PasswordHash $password){
       $this->passwordHash = $password;
    }
    /** ---------------- OTP ---------------- */

    public function generateOtp(): void
    {
        $this->otp = Otp::generate();
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function verifyOtp(string $otpInput): void
    {
        if ($this->isVerified) {
            throw new \DomainException("User already verified");
        }

        if ($this->otp === null) {
            throw new \DomainException("No OTP generated");
        }

        if ($this->otp->isExpired()) {
            throw new \DomainException("OTP expired");
        }

        if (!$this->otp->matches($otpInput)) {
            throw new \DomainException("Invalid OTP");
        }

        $this->isVerified = true;
        $this->otp = null;
        $this->updatedAt = new \DateTimeImmutable();

        $this->recordEvent(new UserVerified($this->id->value()));
    }

    /** ---------------- ROLES ---------------- */

    public function addRole(string $role): void
    {
        $r = strtoupper($role);

        if (isset($this->roles[$r])) {
            throw new RoleAlreadyAssignedException("Role $r already assigned");
        }

        $this->roles[$r] = $r;
        $this->updatedAt = new \DateTimeImmutable();

        $this->recordEvent(new UserRoleAdded($this->id, $r));
    }

    public function removeRole(string $role): void
    {
        $r = strtoupper($role);

        if (!isset($this->roles[$r])) {
            throw new RoleNotAssignedException("Role $r not assigned");
        }

        unset($this->roles[$r]);
        $this->updatedAt = new \DateTimeImmutable();

        $this->recordEvent(new UserRoleRemoved($this->id, $r));
    }

    /** ---------------- EVENTS ---------------- */

    private function recordEvent(object $event): void
    {
        $this->recordedEvents[] = $event;
    }

    public function pullRecordedEvents(): array
    {
        $events = $this->recordedEvents;
        $this->recordedEvents = [];
        return $events;
    }

    /** ---------------- GETTERS ---------------- */

    public function id(): UserId { return $this->id; }
    public function email(): Email { return $this->email; }
    public function passwordHash(): PasswordHash { return $this->passwordHash; }
    public function isVerified(): bool { return $this->isVerified; }
    public function otp(): ?Otp { return $this->otp; }
    public function roles(): array { return array_values($this->roles); }
    public function createdAt(): \DateTimeImmutable { return $this->createdAt; }
    public function updatedAt(): \DateTimeImmutable { return $this->updatedAt; }
}
