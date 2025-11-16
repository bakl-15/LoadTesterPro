<?php 
namespace App\Auth\Domain\User\ValueObject;

use App\SharedKernel\Domain\Validation\Gard;

final class Email
{
     private string $value;
     
     
     public function __construct(string $value)
     {
         Gard::notNullValue($value, "Email");
          $value = strtolower(trim($value));
              if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
              throw new \InvalidArgumentException('Invalid email');
          }
          $this->value = $value;
     }
     
     
     public function value(): string
     {
         return $this->value;
     }
     
     
     public function equals(Email $other): bool
     {
          return $this->value === $other->value();
     }

     public function __toString(): string
     {
        return $this->value;
     }
}