<?php
namespace App\Auth\Domain\User\Service;


use App\Auth\Domain\User\ValueObject\PasswordHash;


interface PasswordHasherInterface
{
    public function hash(string $plain): PasswordHash;
    public function verify(PasswordHash $hash, string $plain): bool;
}