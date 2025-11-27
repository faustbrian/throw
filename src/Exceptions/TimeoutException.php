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
 * Base exception for timeout errors.
 *
 * Thrown when an operation exceeds its time limit. Useful for external
 * API calls, database queries, or any time-bounded operations.
 *
 * @example External service timeout
 * ```php
 * final class ExternalServiceTimeoutException extends TimeoutException
 * {
 *     public static function forService(string $service, int $seconds): self
 *     {
 *         return new self("{$service} request timed out after {$seconds}s");
 *     }
 * }
 * ```
 * @example Lock timeout
 * ```php
 * final class LockTimeoutException extends TimeoutException
 * {
 *     public static function acquiring(string $resource): self
 *     {
 *         return new self("Timeout acquiring lock on {$resource}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class TimeoutException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
