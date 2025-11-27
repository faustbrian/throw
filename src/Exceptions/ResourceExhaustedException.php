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
 * Base exception for resource exhaustion.
 *
 * Thrown when system resources (memory, disk, connections, etc.) are
 * exhausted or limits are reached.
 *
 * @example Memory limit
 * ```php
 * final class MemoryExhaustedException extends ResourceExhaustedException
 * {
 *     public static function limitReached(int $limit): self
 *     {
 *         return new self("Memory limit of {$limit} bytes reached");
 *     }
 * }
 * ```
 * @example Connection pool exhausted
 * ```php
 * final class ConnectionPoolExhaustedException extends ResourceExhaustedException
 * {
 *     public static function noAvailableConnections(): self
 *     {
 *         return new self('No available database connections in pool');
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class ResourceExhaustedException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
