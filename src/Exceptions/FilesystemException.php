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
 * Base exception for general filesystem errors.
 *
 * Thrown when filesystem operations fail, paths are invalid,
 * or filesystem access encounters errors.
 *
 * @example Path not found
 * ```php
 * final class PathNotFoundException extends FilesystemException
 * {
 *     public static function forPath(string $path): self
 *     {
 *         return new self("Path not found: {$path}");
 *     }
 * }
 * ```
 * @example Operation failed
 * ```php
 * final class FilesystemOperationException extends FilesystemException
 * {
 *     public static function failed(string $operation, string $path): self
 *     {
 *         return new self("Filesystem operation '{$operation}' failed for: {$path}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class FilesystemException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
