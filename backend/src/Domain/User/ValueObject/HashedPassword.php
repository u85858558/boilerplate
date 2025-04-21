<?php

declare(strict_types=1);

namespace App\Domain\User\ValueObject;

use App\Domain\User\Exception\InvalidPasswordException;

final class HashedPassword
{
    private string $hashedPassword;

    private function __construct(string $hashedPassword)
    {
        $this->hashedPassword = $hashedPassword;
    }

    public static function encode(string $plainPassword): self
    {
        if (strlen($plainPassword) < 6) {
            throw new InvalidPasswordException('Password must be at least 6 characters long');
        }

        return new self(password_hash($plainPassword, PASSWORD_ARGON2ID));
    }

    public static function fromHash(string $hashedPassword): self
    {
        return new self($hashedPassword);
    }

    public function match(string $plainPassword): bool
    {
        return password_verify($plainPassword, $this->hashedPassword);
    }

    public function toString(): string
    {
        return $this->hashedPassword;
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
