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
 * Base exception for service orchestration failures.
 *
 * Thrown when service orchestration fails, service coordination fails,
 * or orchestration workflows encounter errors.
 *
 * @example Orchestration failed
 * ```php
 * final class OrchestrationFailedException extends OrchestrationException
 * {
 *     public static function forServices(array $services): self
 *     {
 *         $serviceList = implode(', ', $services);
 *         return new self("Orchestration failed for services: {$serviceList}");
 *     }
 * }
 * ```
 * @example Service coordination timeout
 * ```php
 * final class CoordinationTimeoutException extends OrchestrationException
 * {
 *     public static function after(int $timeout): self
 *     {
 *         return new self("Service coordination timed out after {$timeout} seconds");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class OrchestrationException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
