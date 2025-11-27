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
 * Exception for data replication failures.
 *
 * Thrown when replication fails, replica lag exceeds threshold,
 * or replication topology encounters errors.
 *
 * @example Replication failed
 * ```php
 * final class ReplicationFailedException extends ReplicationException
 * {
 *     public static function toReplica(string $replica): self
 *     {
 *         return new self("Replication failed to replica: {$replica}");
 *     }
 * }
 * ```
 * @example Replica lag exceeded
 * ```php
 * final class ReplicaLagException extends ReplicationException
 * {
 *     public static function detected(string $replica, int $lagSeconds): self
 *     {
 *         return new self("Replica '{$replica}' lag exceeded threshold: {$lagSeconds} seconds");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class ReplicationException extends SyncException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
