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
 * Base exception for breaking changes.
 *
 * Thrown when backward compatibility is broken, deprecated features
 * are removed, or incompatible changes are detected.
 *
 * @example Breaking API change
 * ```php
 * final class BreakingChangeException extends BackwardCompatibilityException
 * {
 *     public static function detected(string $feature): self
 *     {
 *         return new self("Breaking change: {$feature} is no longer supported");
 *     }
 * }
 * ```
 * @example Removed feature
 * ```php
 * final class RemovedFeatureException extends BackwardCompatibilityException
 * {
 *     public static function noLongerAvailable(string $feature): self
 *     {
 *         return new self("{$feature} has been removed");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class BackwardCompatibilityException extends VersionException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
