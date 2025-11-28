<?php declare(strict_types=1);

/**
 * Copyright (C) Brian Faust
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cline\Throw\Exceptions;

use Cline\Throw\Concerns\ConditionallyThrowable;
use Cline\Throw\Concerns\HasErrorContext;
use Cline\Throw\Concerns\WrapsErrors;
use RuntimeException;

/**
 * Base exception for broadcasting failures.
 *
 * Thrown when broadcasting events fail via Pusher, Socket.io, Ably,
 * or other real-time broadcasting services.
 *
 * @example Broadcast failed
 * ```php
 * final class BroadcastFailedException extends BroadcastException
 * {
 *     public static function toChannel(string $channel): self
 *     {
 *         return new self("Failed to broadcast to channel: {$channel}");
 *     }
 * }
 * ```
 * @example Connection lost
 * ```php
 * final class BroadcastConnectionException extends BroadcastException
 * {
 *     public static function lost(string $driver): self
 *     {
 *         return new self("Lost connection to {$driver} broadcast service");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class BroadcastException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
