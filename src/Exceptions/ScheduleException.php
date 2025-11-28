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
 * Base exception for job scheduling failures.
 *
 * Thrown when job scheduling fails, scheduled jobs cannot be registered,
 * or schedule operations encounter errors.
 *
 * @example Scheduling failed
 * ```php
 * final class SchedulingFailedException extends ScheduleException
 * {
 *     public static function forJob(string $job): self
 *     {
 *         return new self("Job scheduling failed: {$job}");
 *     }
 * }
 * ```
 * @example Invalid schedule time
 * ```php
 * final class InvalidScheduleTimeException extends ScheduleException
 * {
 *     public static function detected(string $job, string $time): self
 *     {
 *         return new self("Invalid schedule time for job '{$job}': {$time}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class ScheduleException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
