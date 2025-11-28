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
 * Exception for package installation errors.
 *
 * Thrown when package installation fails, package integrity is compromised,
 * or package operations encounter errors.
 *
 * @example Package installation failed
 * ```php
 * final class PackageInstallationException extends PackageException
 * {
 *     public static function failed(string $package, string $version): self
 *     {
 *         return new self("Package installation failed: {$package}@{$version}");
 *     }
 * }
 * ```
 * @example Package integrity check failed
 * ```php
 * final class PackageIntegrityException extends PackageException
 * {
 *     public static function detected(string $package, string $expectedHash, string $actualHash): self
 *     {
 *         return new self("Package '{$package}' integrity check failed: expected={$expectedHash}, actual={$actualHash}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class PackageException extends DependencyException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
