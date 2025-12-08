<?php
namespace App\Auth\Application\User\Command;

use App\Auth\Domain\User\ValueObject\Otp;
use App\Auth\Domain\User\ValueObject\UserId;

final class VerifyOtpCommand
{
    public function __construct(
        public UserId $userId,
        public string $otp
    ) {}
}
