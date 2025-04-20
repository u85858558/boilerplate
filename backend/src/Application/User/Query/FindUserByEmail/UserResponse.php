<?php

declare(strict_types=1);

namespace App\Application\User\Query\FindUserByEmail;

use App\Domain\User\User;

final class UserResponse
{
    private string $id;
    private string $email;
    private array $roles;
    private string $createdAt;
    private ?string $updatedAt;

    private function __construct(
        string $id,
        string $email,
        array $roles,
        string $createdAt,
        ?string $updatedAt
    ) {
        $this->id = $id;
        $this->email = $email;
        $this->roles = $roles;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public static function fromUser(User $user): self
    {
        return new self(
            $user->id()->toString(),
            $user->email()->value(),
            $user->roles(),
            $user->createdAt()->format('Y-m-d H:i:s'),
            $user->updatedAt() ? $user->updatedAt()->format('Y-m-d H:i:s') : null
        );
    }

    public function id(): string
    {
        return $this->id;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function roles(): array
    {
        return $this->roles;
    }

    public function createdAt(): string
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?string
    {
        return $this->updatedAt;
    }
}
