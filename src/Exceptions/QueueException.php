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
 * Base exception for queue operation errors.
 *
 * Thrown when queue operations fail, jobs cannot be queued,
 * or queue management encounters errors.
 *
 * @example Queue full
 * ```php
 * final class QueueFullException extends QueueException
 * {
 *     public static function forQueue(string $queue, int $limit): self
 *     {
 *         return new self("Queue '{$queue}' is full: limit {$limit} reached");
 *     }
 * }
 * ```
 * @example Queue not found
 * ```php
 * final class QueueNotFoundException extends QueueException
 * {
 *     public static function forName(string $name): self
 *     {
 *         return new self("Queue not found: {$name}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class QueueException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
