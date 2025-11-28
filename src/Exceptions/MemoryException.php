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
 * Base exception for memory-specific errors.
 *
 * Thrown when memory limits are exceeded or memory allocation fails.
 * Extends ResourceExhaustedException for memory-specific failures.
 *
 * @example Memory limit exceeded
 * ```php
 * final class MemoryLimitException extends MemoryException
 * {
 *     public static function exceeded(int $limit): self
 *     {
 *         return new self("Memory limit of {$limit} bytes exceeded");
 *     }
 * }
 * ```
 * @example Allocation failed
 * ```php
 * final class MemoryAllocationException extends MemoryException
 * {
 *     public static function failed(int $size): self
 *     {
 *         return new self("Failed to allocate {$size} bytes of memory");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class MemoryException extends ResourceExhaustedException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
