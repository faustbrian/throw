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
 * Base exception for data restoration errors.
 *
 * Thrown when data restoration fails, restore operations encounter errors,
 * or restored data validation fails.
 *
 * @example Restore failed
 * ```php
 * final class RestoreFailedException extends RestoreException
 * {
 *     public static function fromBackup(string $backupId): self
 *     {
 *         return new self("Failed to restore from backup: {$backupId}");
 *     }
 * }
 * ```
 * @example Restore incompatible
 * ```php
 * final class RestoreIncompatibleException extends RestoreException
 * {
 *     public static function version(string $backupVersion, string $currentVersion): self
 *     {
 *         return new self("Cannot restore backup version {$backupVersion} to current version: {$currentVersion}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class RestoreException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
