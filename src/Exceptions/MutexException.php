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
 * Exception for mutex operation failures.
 *
 * Thrown when mutex operations fail, mutual exclusion is violated,
 * or mutex management encounters errors.
 *
 * @example Mutex operation failed
 * ```php
 * final class MutexOperationException extends MutexException
 * {
 *     public static function failed(string $operation, string $mutex): self
 *     {
 *         return new self("Mutex operation '{$operation}' failed for mutex: {$mutex}");
 *     }
 * }
 * ```
 * @example Mutex already locked
 * ```php
 * final class MutexAlreadyLockedException extends MutexException
 * {
 *     public static function detected(string $mutex, string $owner): self
 *     {
 *         return new self("Mutex '{$mutex}' already locked by: {$owner}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class MutexException extends LockException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
