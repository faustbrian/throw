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
 * Exception thrown when type casting fails.
 *
 * Thrown when attempting to cast a value to a different type and the operation
 * fails or would result in data loss. Distinct from type coercion errors.
 *
 * @example Invalid cast
 * ```php
 * final class InvalidCastException extends CastException
 * {
 *     public static function toType(mixed $value, string $targetType): self
 *     {
 *         $sourceType = get_debug_type($value);
 *         return new self("Cannot cast {$sourceType} to {$targetType}");
 *     }
 * }
 * ```
 * @example Lossy conversion
 * ```php
 * final class LossyCastException extends CastException
 * {
 *     public static function detected(string $from, string $to): self
 *     {
 *         return new self("Casting from {$from} to {$to} would result in data loss");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class CastException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
