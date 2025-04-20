<?php

declare(strict_types=1);

namespace App\Domain\User\Exception;

use InvalidArgumentException;

final class InvalidPasswordException extends InvalidArgumentException
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
