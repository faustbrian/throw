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
 * Base exception for data synchronization errors.
 *
 * Thrown when synchronization operations fail, sync targets are out of sync,
 * or sync processes encounter errors.
 *
 * @example Sync failed
 * ```php
 * final class SyncFailedException extends SyncException
 * {
 *     public static function betweenSources(string $source, string $target): self
 *     {
 *         return new self("Synchronization failed between '{$source}' and '{$target}'");
 *     }
 * }
 * ```
 * @example Out of sync detected
 * ```php
 * final class OutOfSyncException extends SyncException
 * {
 *     public static function detected(string $entity, int $sourceVersion, int $targetVersion): self
 *     {
 *         return new self("Entity '{$entity}' out of sync: source={$sourceVersion}, target={$targetVersion}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class SyncException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
