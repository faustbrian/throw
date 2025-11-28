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
 * Base exception for subscription management errors.
 *
 * Thrown when subscription operations fail, subscription changes fail,
 * or subscription lifecycle management encounters errors.
 *
 * @example Subscription not found
 * ```php
 * final class SubscriptionNotFoundException extends SubscriptionException
 * {
 *     public static function forId(string $subscriptionId): self
 *     {
 *         return new self("Subscription not found: {$subscriptionId}");
 *     }
 * }
 * ```
 * @example Subscription change failed
 * ```php
 * final class SubscriptionChangeException extends SubscriptionException
 * {
 *     public static function failed(string $from, string $to): self
 *     {
 *         return new self("Failed to change subscription from '{$from}' to '{$to}'");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class SubscriptionException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
