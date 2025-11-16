<?php
namespace App\Auth\Domain\User\Exception;


use Exception;


final class UserAlreadyExistsException extends Exception
{
    public function __construct(string $email)
    {
           parent::__construct("User with email $email already exists.");
    }
}