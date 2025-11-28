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
 * Exception thrown when filesystem or system permission is denied.
 *
 * Thrown when attempting to perform an operation that requires permissions
 * that the current user or process does not possess. Distinct from HTTP 403.
 *
 * @example File permission denied
 * ```php
 * final class FilePermissionDeniedException extends PermissionDeniedException
 * {
 *     public static function cannotRead(string $path): self
 *     {
 *         return new self("Permission denied: cannot read file '{$path}'");
 *     }
 * }
 * ```
 * @example Directory access denied
 * ```php
 * final class DirectoryAccessDeniedException extends PermissionDeniedException
 * {
 *     public static function insufficientPrivileges(string $directory): self
 *     {
 *         return new self("Insufficient privileges to access directory '{$directory}'");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class PermissionDeniedException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
