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
 * Exception for symbolic link errors.
 *
 * Thrown when symlink operations fail, symlinks are broken,
 * or symlink resolution encounters errors.
 *
 * @example Broken symlink
 * ```php
 * final class BrokenSymlinkException extends SymlinkException
 * {
 *     public static function forLink(string $link, string $target): self
 *     {
 *         return new self("Broken symlink '{$link}' points to non-existent: {$target}");
 *     }
 * }
 * ```
 * @example Symlink creation failed
 * ```php
 * final class SymlinkCreationException extends SymlinkException
 * {
 *     public static function failed(string $link, string $target): self
 *     {
 *         return new self("Failed to create symlink from '{$link}' to '{$target}'");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class SymlinkException extends FilesystemException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
