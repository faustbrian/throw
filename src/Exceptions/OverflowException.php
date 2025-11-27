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
 * Exception for buffer overflow errors.
 *
 * Thrown when buffer overflow occurs, buffer capacity is exceeded,
 * or overflow operations encounter errors.
 *
 * @example Buffer overflow
 * ```php
 * final class BufferOverflowException extends OverflowException
 * {
 *     public static function detected(string $buffer, int $capacity, int $attempted): self
 *     {
 *         return new self("Buffer overflow for '{$buffer}': capacity={$capacity}, attempted={$attempted}");
 *     }
 * }
 * ```
 * @example Overflow handling failed
 * ```php
 * final class OverflowHandlingException extends OverflowException
 * {
 *     public static function failed(string $buffer, string $reason): self
 *     {
 *         return new self("Overflow handling failed for buffer '{$buffer}': {$reason}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class OverflowException extends BackpressureException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
