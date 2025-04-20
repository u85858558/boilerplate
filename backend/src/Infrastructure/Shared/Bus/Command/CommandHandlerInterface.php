<?php
declare(strict_types=1);

namespace App\Infrastructure\Shared\Bus\Command;

/**
 * Marker interface for command handlers
 * 
 * This interface is used to tag services for the command bus.
 * The actual message handling is configured in services.yaml
 * with the messenger.message_handler tag.
 */
interface CommandHandlerInterface
{
}
