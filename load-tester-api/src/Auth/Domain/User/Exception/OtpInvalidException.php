<?php
namespace App\Auth\Domain\User\Exception;


use Exception;


final class OtpInvalidException extends Exception
{
public function __construct()
{
    parent::__construct("Otp is invalid.");
    }
}