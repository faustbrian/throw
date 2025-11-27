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
 * Base exception for file operation errors.
 *
 * Thrown when file operations fail (read, write, delete, move, etc.).
 * Extends InfrastructureException for file system failures.
 *
 * @example File not found
 * ```php
 * final class FileNotFoundException extends FileException
 * {
 *     public static function atPath(string $path): self
 *     {
 *         return new self("File not found: {$path}");
 *     }
 * }
 * ```
 * @example Cannot write file
 * ```php
 * final class FileWriteException extends FileException
 * {
 *     public static function failed(string $path): self
 *     {
 *         return new self("Cannot write to file: {$path}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class FileException extends InfrastructureException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
