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
 * Exception for version conflict errors.
 *
 * Thrown when version conflicts occur, version constraints are violated,
 * or version parsing encounters errors.
 *
 * @example Version conflict detected
 * ```php
 * final class VersionConflictException extends VersionException
 * {
 *     public static function detected(string $package, string $required, string $installed): self
 *     {
 *         return new self("Version conflict for '{$package}': required={$required}, installed={$installed}");
 *     }
 * }
 * ```
 * @example Invalid version constraint
 * ```php
 * final class InvalidVersionConstraintException extends VersionException
 * {
 *     public static function detected(string $constraint): self
 *     {
 *         return new self("Invalid version constraint: {$constraint}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class VersionException extends DependencyException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
