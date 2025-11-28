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
 * Base exception for circuit breaker state errors.
 *
 * Thrown when circuit breaker operations fail, circuit is open,
 * or circuit breaker state transitions encounter errors.
 *
 * @example Circuit breaker open
 * ```php
 * final class CircuitBreakerOpenException extends CircuitBreakerException
 * {
 *     public static function detected(string $circuit, int $failureCount): self
 *     {
 *         return new self("Circuit breaker '{$circuit}' is open after {$failureCount} failures");
 *     }
 * }
 * ```
 * @example Circuit breaker timeout
 * ```php
 * final class CircuitBreakerTimeoutException extends CircuitBreakerException
 * {
 *     public static function detected(string $circuit, int $timeoutMs): self
 *     {
 *         return new self("Circuit breaker '{$circuit}' timeout after {$timeoutMs}ms");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class CircuitBreakerException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
