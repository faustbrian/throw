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
 * Base exception for recurring payment errors.
 *
 * Thrown when recurring payment processing fails, billing cycles fail,
 * or recurring schedule management encounters errors.
 *
 * @example Recurring payment failed
 * ```php
 * final class RecurringPaymentException extends RecurringException
 * {
 *     public static function failed(string $subscriptionId): self
 *     {
 *         return new self("Recurring payment failed for subscription: {$subscriptionId}");
 *     }
 * }
 * ```
 * @example Invalid billing cycle
 * ```php
 * final class InvalidBillingCycleException extends RecurringException
 * {
 *     public static function detected(string $cycle): self
 *     {
 *         return new self("Invalid billing cycle: {$cycle}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class RecurringException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
