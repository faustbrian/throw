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
 * Base exception for resource conflicts.
 *
 * Thrown when an operation cannot complete due to a conflict with the
 * current state of a resource. Maps to HTTP 409 Conflict.
 *
 * @example Duplicate entry
 * ```php
 * final class DuplicateEntryException extends ConflictException
 * {
 *     public static function forField(string $field, mixed $value): self
 *     {
 *         return new self("{$field} '{$value}' already exists");
 *     }
 * }
 * ```
 * @example Concurrent modification
 * ```php
 * final class ConcurrentModificationException extends ConflictException
 * {
 *     public static function detected(): self
 *     {
 *         return new self('Resource was modified by another request');
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class ConflictException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
