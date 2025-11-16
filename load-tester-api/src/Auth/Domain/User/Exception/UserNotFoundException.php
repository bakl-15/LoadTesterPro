<?php
namespace App\Auth\Domain\User\Exception;


use Exception;


final class UserNotFoundException extends Exception
{
public function __construct(string $identifier)
{
   parent::__construct("User not found: $identifier");
   }
}