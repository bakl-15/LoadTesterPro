<?php
// src/Auth/Domain/Role/ValueObject/Permission.php
namespace App\Auth\Domain\Role\ValueObject;

final class Permission
{
    private string $value;

    public function __construct(string $value)
    {
        $v = strtolower(trim($value));
        if ($v === '') {
            throw new \InvalidArgumentException('Permission cannot be empty');
        }
        $this->value = $v;
    }

    public static function fromString(string $value): self
    {
        return new self($value);
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value();
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
