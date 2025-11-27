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
 * Exception for directory operation failures.
 *
 * Thrown when directory operations fail (create, delete, read, etc.),
 * directories don't exist, or directory permissions are insufficient.
 *
 * @example Directory not found
 * ```php
 * final class DirectoryNotFoundException extends DirectoryException
 * {
 *     public static function forPath(string $path): self
 *     {
 *         return new self("Directory not found: {$path}");
 *     }
 * }
 * ```
 * @example Cannot create directory
 * ```php
 * final class DirectoryCreationException extends DirectoryException
 * {
 *     public static function failed(string $path): self
 *     {
 *         return new self("Failed to create directory: {$path}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class DirectoryException extends FilesystemException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
