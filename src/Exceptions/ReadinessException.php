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
 * Exception for readiness probe errors.
 *
 * Thrown when readiness probes fail, services are not ready,
 * or readiness check operations encounter errors.
 *
 * @example Readiness probe failed
 * ```php
 * final class ReadinessProbeException extends ReadinessException
 * {
 *     public static function failed(string $service, string $reason): self
 *     {
 *         return new self("Readiness probe failed for service '{$service}': {$reason}");
 *     }
 * }
 * ```
 * @example Service not ready
 * ```php
 * final class ServiceNotReadyException extends ReadinessException
 * {
 *     public static function detected(string $service, array $pendingDependencies): self
 *     {
 *         return new self("Service '{$service}' not ready: pending dependencies " . implode(', ', $pendingDependencies));
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class ReadinessException extends HealthException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
