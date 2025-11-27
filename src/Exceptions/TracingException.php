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
 * Base exception for distributed tracing errors.
 *
 * Thrown when distributed tracing operations fail (span creation,
 * trace propagation, etc.) or tracing services are unavailable.
 *
 * @example Span creation failed
 * ```php
 * final class SpanCreationException extends TracingException
 * {
 *     public static function failed(string $operation): self
 *     {
 *         return new self("Failed to create span for operation: {$operation}");
 *     }
 * }
 * ```
 * @example Trace export failed
 * ```php
 * final class TraceExportException extends TracingException
 * {
 *     public static function failed(string $exporter): self
 *     {
 *         return new self("Failed to export traces to {$exporter}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class TracingException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
