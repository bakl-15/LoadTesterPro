<?php
namespace App\Auth\Domain\User\ValueObject;

use App\SharedKernel\Domain\Validation\Gard;

final class PasswordHash
{
    private string $hash;
    
    
    public function __construct(string $hash)
    {
        if (empty($hash)) {
             throw new \InvalidArgumentException('Password hash cannot be empty');
        }
         $this->hash = $hash;
    }
    
    public function value(): string
    {
          return $this->hash;
    }

}




