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
 * Base exception for explicitly retryable errors.
 *
 * Thrown when an operation fails but can be safely retried.
 * Signals to retry logic that this failure is transient.
 *
 * @example Transient network error
 * ```php
 * final class TransientNetworkException extends RetryableException
 * {
 *     public static function connectionLost(): self
 *     {
 *         return new self('Connection lost, safe to retry');
 *     }
 * }
 * ```
 * @example Temporary service unavailable
 * ```php
 * final class ServiceTemporarilyUnavailableException extends RetryableException
 * {
 *     public static function tryAgain(int $retryAfter): self
 *     {
 *         return new self("Service unavailable, retry after {$retryAfter} seconds");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class RetryableException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
