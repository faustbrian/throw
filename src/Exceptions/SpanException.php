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
 * Exception for distributed tracing span errors.
 *
 * Thrown when span operations fail, span context is invalid,
 * or span lifecycle management encounters errors.
 *
 * @example Span not found
 * ```php
 * final class SpanNotFoundException extends SpanException
 * {
 *     public static function forId(string $spanId): self
 *     {
 *         return new self("Span not found: {$spanId}");
 *     }
 * }
 * ```
 * @example Invalid span context
 * ```php
 * final class InvalidSpanContextException extends SpanException
 * {
 *     public static function missing(string $field): self
 *     {
 *         return new self("Invalid span context: missing field {$field}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class SpanException extends TracingException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
