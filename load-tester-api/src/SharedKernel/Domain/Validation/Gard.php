<?php

namespace App\SharedKernel\Domain\Validation;

final class Gard
{
    /**
     * Vérifie qu'une valeur string n'est pas null ou vide
     *
     * @param string|null $value
     * @param string $fieldName Nom du champ pour le message d'erreur
     * @throws \InvalidArgumentException
     */
    public static function notNullValue(?string $value, string $fieldName = 'Value'): void
    {
        if ($value === null || trim($value) === '') {
            throw new \InvalidArgumentException(sprintf('%s cannot be null or empty', $fieldName));
        }
    }
}
