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
 * Base exception for file permission errors.
 *
 * Thrown when file operations fail due to insufficient permissions.
 * More specific than FileException for permission-related failures.
 *
 * @example Cannot read file
 * ```php
 * final class FileNotReadableException extends PermissionException
 * {
 *     public static function atPath(string $path): self
 *     {
 *         return new self("File not readable: {$path}");
 *     }
 * }
 * ```
 * @example Cannot write directory
 * ```php
 * final class DirectoryNotWritableException extends PermissionException
 * {
 *     public static function atPath(string $path): self
 *     {
 *         return new self("Directory not writable: {$path}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class PermissionException extends FileException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
