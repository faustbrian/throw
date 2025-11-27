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
 * Exception for message delivery failures.
 *
 * Thrown when message delivery fails, delivery attempts are exhausted,
 * or delivery operations encounter errors.
 *
 * @example Delivery failed
 * ```php
 * final class DeliveryFailedException extends DeliveryException
 * {
 *     public static function afterRetries(string $messageId, int $attempts): self
 *     {
 *         return new self("Message delivery failed for '{$messageId}' after {$attempts} attempts");
 *     }
 * }
 * ```
 * @example Delivery timeout
 * ```php
 * final class DeliveryTimeoutException extends DeliveryException
 * {
 *     public static function detected(string $messageId, int $timeout): self
 *     {
 *         return new self("Message delivery timeout after {$timeout}s for: {$messageId}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class DeliveryException extends NotificationException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
