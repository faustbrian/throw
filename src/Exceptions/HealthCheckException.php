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
 * Base exception for health check failures.
 *
 * Thrown when health checks fail, health status is degraded,
 * or health monitoring encounters errors.
 *
 * @example Health check failed
 * ```php
 * final class HealthCheckFailedException extends HealthCheckException
 * {
 *     public static function forCheck(string $check, string $reason): self
 *     {
 *         return new self("Health check '{$check}' failed: {$reason}");
 *     }
 * }
 * ```
 * @example Unhealthy state
 * ```php
 * final class UnhealthyStateException extends HealthCheckException
 * {
 *     public static function detected(string $service, array $failedChecks): self
 *     {
 *         $checks = implode(', ', $failedChecks);
 *         return new self("Service '{$service}' is unhealthy: failed checks: {$checks}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class HealthCheckException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
