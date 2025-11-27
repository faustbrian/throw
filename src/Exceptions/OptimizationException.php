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
 * Base exception for optimization operation failures.
 *
 * Thrown when optimization operations fail, optimization targets cannot be met,
 * or optimization algorithms encounter errors.
 *
 * @example Optimization failed
 * ```php
 * final class OptimizationFailedException extends OptimizationException
 * {
 *     public static function forTarget(string $target): self
 *     {
 *         return new self("Optimization failed for target: {$target}");
 *     }
 * }
 * ```
 * @example Target not achieved
 * ```php
 * final class TargetNotAchievedException extends OptimizationException
 * {
 *     public static function detected(string $metric, float $target, float $actual): self
 *     {
 *         return new self("Optimization target not achieved for '{$metric}': target={$target}, actual={$actual}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class OptimizationException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
