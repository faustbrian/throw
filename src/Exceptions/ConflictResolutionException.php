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
 * Exception for conflict resolution failures.
 *
 * Thrown when conflict resolution fails, conflicts cannot be automatically resolved,
 * or resolution strategies encounter errors.
 *
 * @example Resolution failed
 * ```php
 * final class ResolutionFailedException extends ConflictResolutionException
 * {
 *     public static function forConflict(string $conflictId): self
 *     {
 *         return new self("Conflict resolution failed for conflict: {$conflictId}");
 *     }
 * }
 * ```
 * @example Manual resolution required
 * ```php
 * final class ManualResolutionRequiredException extends ConflictResolutionException
 * {
 *     public static function detected(string $entity, array $conflicts): self
 *     {
 *         return new self("Manual resolution required for '{$entity}': " . json_encode($conflicts));
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class ConflictResolutionException extends SyncException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
