<?php
// src/Auth/Domain/Role/Repository/RoleRepositoryInterface.php
namespace App\Auth\Domain\Role\Repository;

use App\Auth\Domain\Role\Entity\Role;

interface RoleRepositoryInterface
{
    public function findByName(string $name): ?Role;
    public function findById(string $id): ?Role;
    public function save(Role $role): void;
    public function remove(Role $role): void;
    /** @return Role[] */
    public function findAll(): array;
}
