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
 * Base exception for data transformation errors.
 *
 * Thrown when data transformation fails, transformers encounter errors,
 * or transformation rules are invalid.
 *
 * @example Transformation failed
 * ```php
 * final class TransformationFailedException extends TransformerException
 * {
 *     public static function forField(string $field): self
 *     {
 *         return new self("Data transformation failed for field: {$field}");
 *     }
 * }
 * ```
 * @example Invalid transformation rule
 * ```php
 * final class InvalidTransformationRuleException extends TransformerException
 * {
 *     public static function detected(string $rule): self
 *     {
 *         return new self("Invalid transformation rule: {$rule}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class TransformerException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
