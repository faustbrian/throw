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
 * Base exception for notification delivery failures.
 *
 * Thrown when notification delivery fails, notification channels are unavailable,
 * or notification operations encounter errors.
 *
 * @example Notification delivery failed
 * ```php
 * final class NotificationDeliveryException extends NotificationException
 * {
 *     public static function failed(string $channel, string $recipient): self
 *     {
 *         return new self("Notification delivery failed via '{$channel}' to: {$recipient}");
 *     }
 * }
 * ```
 * @example Channel unavailable
 * ```php
 * final class ChannelUnavailableException extends NotificationException
 * {
 *     public static function detected(string $channel): self
 *     {
 *         return new self("Notification channel unavailable: {$channel}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class NotificationException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
