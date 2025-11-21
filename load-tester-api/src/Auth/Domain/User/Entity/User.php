<?php
// src/Auth/Domain/User/Entity/User.php
namespace App\Auth\Domain\User\Entity;

use App\Auth\Domain\User\ValueObject\UserId;
use App\Auth\Domain\User\ValueObject\Email;
use App\Auth\Domain\User\ValueObject\PasswordHash;
use App\Auth\Domain\User\Exception\RoleAlreadyAssignedException;
use App\Auth\Domain\User\Exception\RoleNotAssignedException;
use App\Auth\Domain\User\Event\UserRoleAdded;
use App\Auth\Domain\User\Event\UserRoleRemoved;

final class User
{
    private UserId $id;
    private Email $email;
    private PasswordHash $passwordHash;
    private bool $isVerified = false;
    private array $recordedEvents = [];

    /** @var string[] map roleName => roleName */
    private array $roles = [];

    private \DateTimeImmutable $createdAt;
    private \DateTimeImmutable $updatedAt;

    private function __construct(UserId $id, Email $email, PasswordHash $passwordHash, array $roles = [])
    {
        $this->id = $id;
        $this->email = $email;
        $this->passwordHash = $passwordHash;
        $this->roles = [];
        foreach ($roles as $r) {
            $this->roles[strtoupper($r)] = strtoupper($r);
        }
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    public static function register(UserId $id, Email $email, PasswordHash $passwordHash, array $roles = []): self
    {
        $u = new self($id, $email, $passwordHash, $roles);
        // event user registered omitted for brevity
        return $u;
    }

    // ROLES API

    /** @return string[] */
    public function roles(): array
    {
        return array_values($this->roles);
    }

    public function hasRole(string $roleName): bool
    {
        $r = strtoupper(trim($roleName));
        return isset($this->roles[$r]);
    }

    /**
     * NOTE: addRole expects the Role name (string). Handler should validate Role exists.
     */
    public function addRole(string $roleName): void
    {
        $r = strtoupper(trim($roleName));
        if ($this->hasRole($r)) {
            throw new RoleAlreadyAssignedException("Role $r already assigned");
        }
        $this->roles[$r] = $r;
        $this->updatedAt = new \DateTimeImmutable();
        $this->recordEvent(new UserRoleAdded($this->id, $r));
    }

    public function removeRole(string $roleName): void
    {
        $r = strtoupper(trim($roleName));
        if (!$this->hasRole($r)) {
            throw new RoleNotAssignedException("Role $r not assigned");
        }
        unset($this->roles[$r]);
        $this->updatedAt = new \DateTimeImmutable();
        $this->recordEvent(new UserRoleRemoved($this->id, $r));
    }

    /**
     * Retourne toutes les permissions cumulées (strings) depuis les rôles
     * ATTENTION : ce code suppose que tu passes en paramètre les roles chargés
     * depuis le RoleRepository. Ici c'est utilitaire ; l'aggregation est faite
     * en Application Handler (où tu as accès au RoleRepository).
     *
     * Exemple d'utilisation : $user->permissionsFromRoles($loadedRoles)
     *
     * @param array<string, \App\Auth\Domain\Role\Entity\Role> $loadedRoles map name=>Role
     * @return string[] unique
     */
    public function permissionsFromRoles(array $loadedRoles): array
    {
        $perms = [];
        foreach ($this->roles as $rName) {
            $key = strtoupper($rName);
            if (!isset($loadedRoles[$key])) continue;
            $role = $loadedRoles[$key];
            foreach ($role->permissions() as $p) {
                $perms[$p->value()] = $p->value();
            }
        }
        return array_values($perms);
    }

    // events & getters
    private function recordEvent(object $e): void { $this->recordedEvents[] = $e; }
    public function pullRecordedEvents(): array { $e = $this->recordedEvents; $this->recordedEvents = []; return $e; }

    public function id(): UserId { return $this->id; }
    public function email(): Email { return $this->email; }
    public function isVerified(): bool { return $this->isVerified; }
    public function createdAt(): \DateTimeImmutable { return $this->createdAt; }
    public function updatedAt(): \DateTimeImmutable { return $this->updatedAt; }
}
