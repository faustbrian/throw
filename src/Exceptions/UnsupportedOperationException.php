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
 * Base exception for unsupported operations.
 *
 * Thrown when a requested operation is not supported or not implemented.
 * Clearer than BadMethodCallException for feature availability.
 *
 * @example Read-only resource
 * ```php
 * final class ReadOnlyException extends UnsupportedOperationException
 * {
 *     public static function cannotModify(): self
 *     {
 *         return new self('Cannot modify read-only resource');
 *     }
 * }
 * ```
 * @example Feature not available
 * ```php
 * final class FeatureNotAvailableException extends UnsupportedOperationException
 * {
 *     public static function forPlan(string $feature, string $plan): self
 *     {
 *         return new self("{$feature} not available on {$plan} plan");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class UnsupportedOperationException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
