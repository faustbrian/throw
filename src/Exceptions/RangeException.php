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
use RangeException as PhpRangeException;

/**
 * Exception thrown to indicate range errors during program execution.
 *
 * Thrown during runtime when a value is outside an acceptable range. This differs from
 * OutOfRangeException in that it indicates a runtime error rather than a logic error.
 *
 * @example Value out of range
 * ```php
 * final class ValueOutOfRangeException extends RangeException
 * {
 *     public static function between(int $value, int $min, int $max): self
 *     {
 *         return new self("Value {$value} is outside the range {$min}-{$max}");
 *     }
 * }
 * ```
 * @example Calculation overflow
 * ```php
 * final class CalculationRangeException extends RangeException
 * {
 *     public static function tooLarge(string $operation): self
 *     {
 *         return new self("Result of {$operation} exceeds acceptable range");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class RangeException extends PhpRangeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
