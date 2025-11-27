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
 * Base exception for payment processing errors.
 *
 * Thrown when payment processing fails, cards are declined,
 * or payment gateway operations encounter errors.
 *
 * @example Payment declined
 * ```php
 * final class PaymentDeclinedException extends PaymentException
 * {
 *     public static function insufficientFunds(): self
 *     {
 *         return new self('Payment declined: insufficient funds');
 *     }
 * }
 * ```
 * @example Card validation failed
 * ```php
 * final class CardValidationException extends PaymentException
 * {
 *     public static function invalid(): self
 *     {
 *         return new self('Credit card validation failed');
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class PaymentException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
