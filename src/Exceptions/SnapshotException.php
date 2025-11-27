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
 * Base exception for snapshot operation failures.
 *
 * Thrown when snapshot operations fail, snapshots cannot be created,
 * or snapshot management encounters errors.
 *
 * @example Snapshot creation failed
 * ```php
 * final class SnapshotCreationException extends SnapshotException
 * {
 *     public static function failed(string $resource): self
 *     {
 *         return new self("Failed to create snapshot for resource: {$resource}");
 *     }
 * }
 * ```
 * @example Snapshot not found
 * ```php
 * final class SnapshotNotFoundException extends SnapshotException
 * {
 *     public static function forId(string $snapshotId): self
 *     {
 *         return new self("Snapshot not found: {$snapshotId}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class SnapshotException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
