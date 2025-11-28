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
 * Base exception for concurrent operation failures.
 *
 * Thrown when concurrent operations fail, synchronization fails,
 * or parallel execution encounters errors.
 *
 * @example Concurrent modification
 * ```php
 * final class ConcurrentModificationException extends ConcurrencyException
 * {
 *     public static function detected(string $resource): self
 *     {
 *         return new self("Concurrent modification detected on resource: {$resource}");
 *     }
 * }
 * ```
 * @example Synchronization failed
 * ```php
 * final class SynchronizationException extends ConcurrencyException
 * {
 *     public static function failed(string $operation): self
 *     {
 *         return new self("Synchronization failed during operation: {$operation}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class ConcurrencyException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
