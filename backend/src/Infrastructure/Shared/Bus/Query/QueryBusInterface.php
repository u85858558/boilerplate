<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Bus\Query;

interface QueryBusInterface
{
    /**
     * @throws \Throwable
     */
    public function ask(QueryInterface $query): mixed;
}
