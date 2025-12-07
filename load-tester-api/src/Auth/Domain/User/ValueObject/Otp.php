<?php
namespace App\Auth\Domain\User\ValueObject;

final class Otp
{
    private string $code;
    private \DateTimeImmutable $expiresAt;

    private function __construct(string $code, \DateTimeImmutable $expiresAt)
    {
        $this->code = $code;
        $this->expiresAt = $expiresAt;
    }

    public static function generate(int $length = 6, int $ttlSeconds = 300): self
    {
        $code = str_pad((string)random_int(0, (int) str_repeat('9', $length)), $length, '0', STR_PAD_LEFT);
        $expiresAt = new \DateTimeImmutable("+{$ttlSeconds} seconds");
        return new self($code, $expiresAt);
    }

    public function code(): string
    {
        return $this->code;
    }

    public function isExpired(): bool
    {
        return new \DateTimeImmutable() > $this->expiresAt;
    }

    public function matches(string $input): bool
    {
        return $this->code === $input;
    }
}
