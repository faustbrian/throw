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
 * Base exception for business logic errors.
 *
 * Domain exceptions represent violations of business rules, invalid state
 * transitions, or other domain-specific error conditions. These errors
 * indicate problems with business logic rather than technical failures.
 *
 * @example Invalid state transition
 * ```php
 * final class OrderCannotBeCancelledException extends DomainException
 * {
 *     public static function alreadyShipped(): self
 *     {
 *         return new self('Order cannot be cancelled after shipping');
 *     }
 * }
 * ```
 * @example Business rule violation
 * ```php
 * final class InsufficientFundsException extends DomainException
 * {
 *     public static function forAmount(Money $amount): self
 *     {
 *         return new self("Insufficient funds: {$amount->format()}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class DomainException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
