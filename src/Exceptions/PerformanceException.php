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
 * Base exception for performance threshold violations.
 *
 * Thrown when operations exceed performance thresholds (execution time,
 * query count, memory usage, etc.).
 *
 * @example Slow query detected
 * ```php
 * final class SlowQueryException extends PerformanceException
 * {
 *     public static function detected(float $duration, float $threshold): self
 *     {
 *         return new self("Slow query: {$duration}ms exceeds {$threshold}ms threshold");
 *     }
 * }
 * ```
 * @example N+1 query detected
 * ```php
 * final class NPlusOneQueryException extends PerformanceException
 * {
 *     public static function detected(int $count): self
 *     {
 *         return new self("N+1 query detected: {$count} queries executed");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class PerformanceException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
