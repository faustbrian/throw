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
use OverflowException as PhpOverflowException;

/**
 * Exception thrown when adding an element to a full container.
 *
 * Thrown during runtime when attempting to add elements to a container that has
 * reached its maximum capacity or when a computational overflow occurs.
 *
 * @example Container full
 * ```php
 * final class ContainerFullException extends OverflowException
 * {
 *     public static function withCapacity(int $capacity): self
 *     {
 *         return new self("Container is full (capacity: {$capacity})");
 *     }
 * }
 * ```
 * @example Buffer overflow
 * ```php
 * final class BufferOverflowException extends OverflowException
 * {
 *     public static function exceeded(int $size, int $limit): self
 *     {
 *         return new self("Buffer overflow: {$size} bytes exceeds limit of {$limit}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class OverflowException extends PhpOverflowException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
