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
 * Base exception for feature targeting errors.
 *
 * Thrown when feature targeting fails, targeting rules are invalid,
 * or targeting evaluation encounters errors.
 *
 * @example Invalid targeting rule
 * ```php
 * final class InvalidTargetingRuleException extends TargetingException
 * {
 *     public static function forFeature(string $feature, string $rule): self
 *     {
 *         return new self("Invalid targeting rule for feature '{$feature}': {$rule}");
 *     }
 * }
 * ```
 * @example Targeting evaluation failed
 * ```php
 * final class TargetingEvaluationException extends TargetingException
 * {
 *     public static function failed(string $feature, string $user): self
 *     {
 *         return new self("Failed to evaluate targeting for feature '{$feature}' and user: {$user}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class TargetingException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
