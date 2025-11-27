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
 * Exception for data transformation failures.
 *
 * Thrown when data transformation fails, transformation rules are invalid,
 * or transformation operations encounter errors.
 *
 * @example Transformation failed
 * ```php
 * final class TransformationFailedException extends TransformationException
 * {
 *     public static function forField(string $field, string $transformation): self
 *     {
 *         return new self("Transformation '{$transformation}' failed for field: {$field}");
 *     }
 * }
 * ```
 * @example Invalid transformation rule
 * ```php
 * final class InvalidTransformationRuleException extends TransformationException
 * {
 *     public static function detected(string $rule, string $reason): self
 *     {
 *         return new self("Invalid transformation rule '{$rule}': {$reason}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class TransformationException extends ConversionException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
