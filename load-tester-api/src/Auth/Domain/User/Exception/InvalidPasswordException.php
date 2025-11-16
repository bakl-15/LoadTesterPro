<?php
namespace App\Auth\Domain\User\Exception;


use Exception;


final class InvalidPasswordException extends Exception
{
public function __construct()
{
    parent::__construct("The provided password is invalid.");
    }
}