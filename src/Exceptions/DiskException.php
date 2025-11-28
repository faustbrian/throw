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
 * Base exception for disk space and quota errors.
 *
 * Thrown when disk space is exhausted, disk quotas are exceeded,
 * or disk operations encounter space-related errors.
 *
 * @example Disk full
 * ```php
 * final class DiskFullException extends DiskException
 * {
 *     public static function onMount(string $mount): self
 *     {
 *         return new self("Disk full on mount point: {$mount}");
 *     }
 * }
 * ```
 * @example Quota exceeded
 * ```php
 * final class DiskQuotaExceededException extends DiskException
 * {
 *     public static function forUser(string $user, int $quota): self
 *     {
 *         return new self("Disk quota of {$quota} bytes exceeded for user: {$user}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class DiskException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
