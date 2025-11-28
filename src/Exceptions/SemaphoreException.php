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
 * Exception for semaphore operation errors.
 *
 * Thrown when semaphore operations fail, permits are unavailable,
 * or semaphore management encounters errors.
 *
 * @example Semaphore operation failed
 * ```php
 * final class SemaphoreOperationException extends SemaphoreException
 * {
 *     public static function failed(string $operation, string $semaphore): self
 *     {
 *         return new self("Semaphore operation '{$operation}' failed for semaphore: {$semaphore}");
 *     }
 * }
 * ```
 * @example No permits available
 * ```php
 * final class NoPermitsAvailableException extends SemaphoreException
 * {
 *     public static function detected(string $semaphore, int $maxPermits): self
 *     {
 *         return new self("No permits available for semaphore '{$semaphore}': all {$maxPermits} permits in use");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class SemaphoreException extends LockException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
