<?php
declare(strict_types=1);

namespace App\Domain\Shared;

use Symfony\Component\Uid\Uuid;

class IdGenerator
{
    public function generate(): Uuid
    {
        return Uuid::v6();
    }
}