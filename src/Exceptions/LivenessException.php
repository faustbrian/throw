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
 * Exception for liveness probe failures.
 *
 * Thrown when liveness probes fail, services are unresponsive,
 * or liveness check operations encounter errors.
 *
 * @example Liveness probe failed
 * ```php
 * final class LivenessProbeException extends LivenessException
 * {
 *     public static function failed(string $service, int $consecutiveFailures): self
 *     {
 *         return new self("Liveness probe failed for service '{$service}': {$consecutiveFailures} consecutive failures");
 *     }
 * }
 * ```
 * @example Service unresponsive
 * ```php
 * final class ServiceUnresponsiveException extends LivenessException
 * {
 *     public static function detected(string $service, int $timeoutSeconds): self
 *     {
 *         return new self("Service '{$service}' unresponsive after {$timeoutSeconds} seconds");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class LivenessException extends HealthException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
