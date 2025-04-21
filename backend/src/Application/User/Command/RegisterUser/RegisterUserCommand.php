<?php

declare(strict_types=1);

namespace App\Application\User\Command\RegisterUser;

use App\Infrastructure\Shared\Bus\Command\CommandInterface;

final class RegisterUserCommand implements CommandInterface
{
    private string $email;
    private string $plainPassword;
    private array $roles;

    public function __construct(string $email, string $plainPassword, array $roles = ['ROLE_USER'])
    {
        $this->email = $email;
        $this->plainPassword = $plainPassword;
        $this->roles = $roles;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function plainPassword(): string
    {
        return $this->plainPassword;
    }

    public function roles(): array
    {
        return $this->roles;
    }
}
