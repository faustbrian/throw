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
 * Base exception for feature rollout failures.
 *
 * Thrown when feature rollout operations fail, rollout percentages are invalid,
 * or rollout evaluation encounters errors.
 *
 * @example Rollout failed
 * ```php
 * final class RolloutFailedException extends RolloutException
 * {
 *     public static function forFeature(string $feature, int $percentage): self
 *     {
 *         return new self("Failed to rollout feature '{$feature}' to {$percentage}%");
 *     }
 * }
 * ```
 * @example Invalid rollout percentage
 * ```php
 * final class InvalidRolloutPercentageException extends RolloutException
 * {
 *     public static function outOfRange(int $percentage): self
 *     {
 *         return new self("Invalid rollout percentage {$percentage}: must be between 0 and 100");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class RolloutException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
