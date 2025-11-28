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
 * Exception thrown for arithmetic errors.
 *
 * Thrown when an arithmetic operation fails due to invalid operands, overflow,
 * division by zero, or other mathematical errors during computation.
 *
 * @example Division by zero
 * ```php
 * final class DivisionByZeroException extends ArithmeticException
 * {
 *     public static function attempted(int|float $dividend): self
 *     {
 *         return new self("Cannot divide {$dividend} by zero");
 *     }
 * }
 * ```
 * @example Numeric overflow
 * ```php
 * final class NumericOverflowException extends ArithmeticException
 * {
 *     public static function detected(string $operation): self
 *     {
 *         return new self("Numeric overflow detected in {$operation}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class ArithmeticException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
