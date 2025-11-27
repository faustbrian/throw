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

/**
 * Base exception for presence channel errors.
 *
 * Thrown when presence channel operations fail, user presence cannot
 * be updated, or presence authorization fails.
 *
 * @example Presence authorization failed
 * ```php
 * final class PresenceAuthorizationException extends PresenceException
 * {
 *     public static function denied(string $channel): self
 *     {
 *         return new self("Presence authorization denied for channel: {$channel}");
 *     }
 * }
 * ```
 * @example Cannot update presence
 * ```php
 * final class PresenceUpdateException extends PresenceException
 * {
 *     public static function failed(string $user): self
 *     {
 *         return new self("Failed to update presence for user: {$user}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class PresenceException extends BroadcastException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
