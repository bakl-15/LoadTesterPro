<?php
// src/Auth/Domain/Role/Entity/Role.php
namespace App\Auth\Domain\Role\Entity;

use App\Auth\Domain\Role\ValueObject\Permission;
use Ramsey\Uuid\Uuid;

final class Role
{
    private string $id;
    private string $name; // e.g. ROLE_ADMIN
    /** @var Permission[] indexed by value */
    private array $permissions = [];

    private function __construct(string $id, string $name, array $permissions = [])
    {
        $this->id = $id;
        $this->name = strtoupper(trim($name));
        foreach ($permissions as $p) {
            if (!$p instanceof Permission) {
                throw new \InvalidArgumentException('Permissions must be Permission VO');
            }
            $this->permissions[$p->value()] = $p;
        }
    }

    public static function create(string $name, array $permissions = []): self
    {
        return new self(Uuid::uuid4()->toString(), $name, $permissions);
    }

    public static function fromPersistence(string $id, string $name, array $permissionValues): self
    {
        $perms = array_map(fn($v) => new Permission($v), $permissionValues);
        return new self($id, $name, $perms);
    }

    public function id(): string { return $this->id; }
    public function name(): string { return $this->name; }

    /** @return Permission[] */
    public function permissions(): array { return array_values($this->permissions); }

    public function addPermission(Permission $p): void
    {
        $this->permissions[$p->value()] = $p;
    }

    public function removePermission(Permission $p): void
    {
        unset($this->permissions[$p->value()]);
    }

    public function hasPermission(Permission $p): bool
    {
        return isset($this->permissions[$p->value()]);
    }
}
