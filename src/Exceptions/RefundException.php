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
 * Base exception for refund processing failures.
 *
 * Thrown when refund operations fail, refund amounts are invalid,
 * or refund processing encounters errors.
 *
 * @example Refund processing failed
 * ```php
 * final class RefundProcessingException extends RefundException
 * {
 *     public static function failed(string $paymentId): self
 *     {
 *         return new self("Failed to process refund for payment: {$paymentId}");
 *     }
 * }
 * ```
 * @example Invalid refund amount
 * ```php
 * final class InvalidRefundAmountException extends RefundException
 * {
 *     public static function exceedsOriginal(float $amount, float $max): self
 *     {
 *         return new self("Refund amount {$amount} exceeds original {$max}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class RefundException extends PaymentException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
