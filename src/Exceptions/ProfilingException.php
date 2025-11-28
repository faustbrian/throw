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
 * Base exception for performance profiling failures.
 *
 * Thrown when profiling operations fail, profilers cannot start,
 * or profiling data collection encounters errors.
 *
 * @example Profiler not available
 * ```php
 * final class ProfilerNotAvailableException extends ProfilingException
 * {
 *     public static function forExtension(string $extension): self
 *     {
 *         return new self("Profiler extension not available: {$extension}");
 *     }
 * }
 * ```
 * @example Profiling data export failed
 * ```php
 * final class ProfilingExportException extends ProfilingException
 * {
 *     public static function failed(string $format): self
 *     {
 *         return new self("Failed to export profiling data in format: {$format}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class ProfilingException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
