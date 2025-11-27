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
 * Base exception for immutability violations.
 *
 * Thrown when attempting to modify immutable objects, value objects,
 * or frozen/locked resources.
 *
 * @example Immutable value object
 * ```php
 * final class ValueObjectImmutableException extends ImmutableException
 * {
 *     public static function cannotModify(string $class): self
 *     {
 *         return new self("{$class} is immutable and cannot be modified");
 *     }
 * }
 * ```
 * @example Frozen resource
 * ```php
 * final class ResourceFrozenException extends ImmutableException
 * {
 *     public static function cannotUpdate(): self
 *     {
 *         return new self('Cannot update frozen resource');
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class ImmutableException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
