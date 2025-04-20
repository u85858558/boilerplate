<?php

declare(strict_types=1);

namespace App\Domain\User;

use App\Domain\User\ValueObject\Email;
use App\Domain\User\ValueObject\HashedPassword;
use App\Domain\User\ValueObject\UserId;
use DateTimeImmutable;

final class User
{
    private UserId $id;
    private Email $email;
    private HashedPassword $password;
    private array $roles;
    private DateTimeImmutable $createdAt;
    private ?DateTimeImmutable $updatedAt;

    private function __construct(
        UserId $id,
        Email $email,
        HashedPassword $password,
        array $roles = ['ROLE_USER'],
        ?DateTimeImmutable $createdAt = null,
        ?DateTimeImmutable $updatedAt = null
    ) {
        $this->id = $id;
        $this->email = $email;
        $this->password = $password;
        $this->roles = $roles;
        $this->createdAt = $createdAt ?? new DateTimeImmutable();
        $this->updatedAt = $updatedAt;
    }

    public static function create(
        UserId $id,
        Email $email,
        HashedPassword $password,
        array $roles = ['ROLE_USER']
    ): self {
        return new self($id, $email, $password, $roles);
    }

    public static function fromPrimitives(
        string $id,
        string $email,
        string $hashedPassword,
        array $roles = ['ROLE_USER'],
        string $createdAt = null,
        string $updatedAt = null
    ): self {
        return new self(
            UserId::fromString($id),
            Email::fromString($email),
            HashedPassword::fromHash($hashedPassword),
            $roles,
            $createdAt ? new DateTimeImmutable($createdAt) : null,
            $updatedAt ? new DateTimeImmutable($updatedAt) : null
        );
    }

    public function id(): UserId
    {
        return $this->id;
    }

    public function email(): Email
    {
        return $this->email;
    }

    public function password(): HashedPassword
    {
        return $this->password;
    }

    public function roles(): array
    {
        return $this->roles;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function changeEmail(Email $email): void
    {
        $this->email = $email;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function changePassword(HashedPassword $password): void
    {
        $this->password = $password;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function addRole(string $role): void
    {
        if (!in_array($role, $this->roles, true)) {
            $this->roles[] = $role;
            $this->updatedAt = new DateTimeImmutable();
        }
    }

    public function removeRole(string $role): void
    {
        if (($key = array_search($role, $this->roles, true)) !== false) {
            unset($this->roles[$key]);
            $this->roles = array_values($this->roles);
            $this->updatedAt = new DateTimeImmutable();
        }
    }
}
