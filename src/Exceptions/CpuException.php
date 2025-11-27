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
 * Base exception for CPU resource errors.
 *
 * Thrown when CPU resources are exhausted, CPU limits are exceeded,
 * or CPU-intensive operations fail.
 *
 * @example CPU limit exceeded
 * ```php
 * final class CpuLimitExceededException extends CpuException
 * {
 *     public static function withUsage(float $usage, float $limit): self
 *     {
 *         return new self("CPU usage {$usage}% exceeds limit: {$limit}%");
 *     }
 * }
 * ```
 * @example CPU throttling
 * ```php
 * final class CpuThrottledException extends CpuException
 * {
 *     public static function detected(): self
 *     {
 *         return new self('CPU throttling detected due to resource constraints');
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class CpuException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
