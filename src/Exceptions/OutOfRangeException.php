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
use OutOfRangeException as PhpOutOfRangeException;

/**
 * Exception thrown when an illegal index was requested.
 *
 * Thrown when an index or offset is outside the valid range for a data structure.
 * This is a logic exception indicating a programming error during development.
 *
 * @example Array index out of range
 * ```php
 * final class IndexOutOfRangeException extends OutOfRangeException
 * {
 *     public static function at(int $index, int $max): self
 *     {
 *         return new self("Index {$index} is out of range (0-{$max})");
 *     }
 * }
 * ```
 * @example Offset violation
 * ```php
 * final class InvalidOffsetException extends OutOfRangeException
 * {
 *     public static function negative(int $offset): self
 *     {
 *         return new self("Offset cannot be negative, got {$offset}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class OutOfRangeException extends PhpOutOfRangeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
