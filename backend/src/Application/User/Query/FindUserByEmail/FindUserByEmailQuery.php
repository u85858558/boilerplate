<?php

declare(strict_types=1);

namespace App\Application\User\Query\FindUserByEmail;

use App\Infrastructure\Shared\Bus\Query\QueryInterface;

final class FindUserByEmailQuery implements QueryInterface
{
    private string $email;

    public function __construct(string $email)
    {
        $this->email = $email;
    }

    public function email(): string
    {
        return $this->email;
    }
}
