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
 * Base exception for dependency resolution failures.
 *
 * Thrown when dependency resolution fails, dependencies are missing,
 * or dependency conflicts are detected.
 *
 * @example Dependency resolution failed
 * ```php
 * final class DependencyResolutionException extends DependencyException
 * {
 *     public static function failed(string $package): self
 *     {
 *         return new self("Dependency resolution failed for package: {$package}");
 *     }
 * }
 * ```
 * @example Missing dependency
 * ```php
 * final class MissingDependencyException extends DependencyException
 * {
 *     public static function detected(string $package, string $dependency): self
 *     {
 *         return new self("Package '{$package}' missing dependency: {$dependency}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class DependencyException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
