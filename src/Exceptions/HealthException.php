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
 * or health check operations encounter errors.
 *
 * @example Health check failed
 * ```php
 * final class HealthCheckFailedException extends HealthException
 * {
 *     public static function forComponent(string $component, string $reason): self
 *     {
 *         return new self("Health check failed for component '{$component}': {$reason}");
 *     }
 * }
 * ```
 * @example Service unhealthy
 * ```php
 * final class ServiceUnhealthyException extends HealthException
 * {
 *     public static function detected(string $service, array $failures): self
 *     {
 *         return new self("Service '{$service}' is unhealthy: " . json_encode($failures));
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class HealthException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
