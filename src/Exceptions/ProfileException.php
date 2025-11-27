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
 * Base exception for profiling operation errors.
 *
 * Thrown when profiling operations fail, profiler initialization encounters errors,
 * or profiling data collection fails.
 *
 * @example Profiling operation failed
 * ```php
 * final class ProfilingOperationException extends ProfileException
 * {
 *     public static function failed(string $operation, string $profiler): self
 *     {
 *         return new self("Profiling operation '{$operation}' failed for profiler: {$profiler}");
 *     }
 * }
 * ```
 * @example Profiler not available
 * ```php
 * final class ProfilerNotAvailableException extends ProfileException
 * {
 *     public static function detected(string $profiler): self
 *     {
 *         return new self("Profiler not available: {$profiler}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class ProfileException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
