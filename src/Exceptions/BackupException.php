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
 * Base exception for backup operation failures.
 *
 * Thrown when backup operations fail, backups cannot be created,
 * or backup verification encounters errors.
 *
 * @example Backup failed
 * ```php
 * final class BackupFailedException extends BackupException
 * {
 *     public static function forResource(string $resource): self
 *     {
 *         return new self("Backup failed for resource: {$resource}");
 *     }
 * }
 * ```
 * @example Backup verification failed
 * ```php
 * final class BackupVerificationException extends BackupException
 * {
 *     public static function corrupted(string $backupId): self
 *     {
 *         return new self("Backup verification failed, backup corrupted: {$backupId}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class BackupException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
