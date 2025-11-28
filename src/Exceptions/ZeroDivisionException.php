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
 * Exception thrown when dividing by zero.
 *
 * Thrown when attempting to divide a number by zero, which is mathematically
 * undefined. This is a specific case of ArithmeticException.
 *
 * @example Division by zero
 * ```php
 * final class DivideByZeroException extends ZeroDivisionException
 * {
 *     public static function withDividend(int|float $dividend): self
 *     {
 *         return new self("Division by zero: {$dividend} / 0");
 *     }
 * }
 * ```
 * @example Modulo by zero
 * ```php
 * final class ModuloByZeroException extends ZeroDivisionException
 * {
 *     public static function attempted(int $value): self
 *     {
 *         return new self("Modulo by zero: {$value} % 0");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class ZeroDivisionException extends ArithmeticException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
