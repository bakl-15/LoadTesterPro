<?php
namespace App\Auth\Domain\User\Exception;


use Exception;


final class InvalidCredentialsException extends Exception
{
    public function __construct(string $email)
    {
           parent::__construct("Invalid Credentials");
    }
}