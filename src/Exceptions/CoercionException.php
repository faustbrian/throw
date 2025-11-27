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
 * Exception for type coercion errors.
 *
 * Thrown when type coercion fails, types are incompatible,
 * or coercion operations encounter errors.
 *
 * @example Coercion failed
 * ```php
 * final class CoercionFailedException extends CoercionException
 * {
 *     public static function forValue(mixed $value, string $targetType): self
 *     {
 *         return new self("Type coercion failed for value " . json_encode($value) . " to type: {$targetType}");
 *     }
 * }
 * ```
 * @example Incompatible types
 * ```php
 * final class IncompatibleTypesException extends CoercionException
 * {
 *     public static function detected(string $sourceType, string $targetType): self
 *     {
 *         return new self("Incompatible types for coercion: cannot coerce '{$sourceType}' to '{$targetType}'");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class CoercionException extends ConversionException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
