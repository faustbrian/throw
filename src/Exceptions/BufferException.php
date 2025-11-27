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
 * Base exception for buffer overflow and underflow.
 *
 * Thrown when buffer operations fail, buffer capacity is exceeded,
 * or buffer reads encounter underflow.
 *
 * @example Buffer overflow
 * ```php
 * final class BufferOverflowException extends BufferException
 * {
 *     public static function withSize(int $size, int $capacity): self
 *     {
 *         return new self("Buffer overflow: size {$size} exceeds capacity {$capacity}");
 *     }
 * }
 * ```
 * @example Buffer underflow
 * ```php
 * final class BufferUnderflowException extends BufferException
 * {
 *     public static function cannotRead(int $requested, int $available): self
 *     {
 *         return new self("Buffer underflow: requested {$requested} bytes, only {$available} available");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class BufferException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
