<?php
declare(strict_types=1);

namespace App\Infrastructure\Shared\Bus\Command;

/**
 * Marker interface for command handlers
 * 
 * Note: We don't extend Symfony's MessageHandlerInterface directly anymore
 * as it's not necessary. Symfony will detect handlers through the #[AsMessageHandler]
 * attribute or through service configuration.
 */
interface CommandHandlerInterface
{
}
