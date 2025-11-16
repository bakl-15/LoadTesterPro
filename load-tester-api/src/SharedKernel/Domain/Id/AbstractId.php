<?php

namespace App\SharedKernel\Domain\Id;

use Ramsey\Uuid\Uuid;

abstract class AbstractId
{
    private string $value;

    /**
     * AbstractId constructor.
     * @param string $value
     */
    protected function __construct(string $value)
    {
        if (!Uuid::isValid($value)) {
            throw new \InvalidArgumentException(sprintf(
                'Invalid UUID "%s" for %s',
                $value,
                static::class
            ));
        }

        $this->value = $value;
    }

    /**
     * Génère un nouvel UUID
     */
    public static function generate(): static
    {
        return new static(Uuid::uuid4()->toString());
    }

    /**
     * Crée à partir d'une string
     */
    public static function fromString(string $value): static
    {
        return new static($value);
    }

    /**
     * Retourne la valeur en string
     */
    public function value(): string
    {
        return $this->value;
    }

    /**
     * Comparaison avec un autre ID
     */
    public function equals(self $other): bool
    {
        return $this->value === $other->value();
    }

    /**
     * Permet de traiter l'objet comme une string
     */
    public function __toString(): string
    {
        return $this->value;
    }
}
