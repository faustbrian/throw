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
 * Base exception for feature flag errors.
 *
 * Thrown when feature flag operations fail, flags are not found,
 * or flag evaluation encounters errors.
 *
 * @example Feature flag not found
 * ```php
 * final class FeatureFlagNotFoundException extends FeatureFlagException
 * {
 *     public static function forKey(string $key): self
 *     {
 *         return new self("Feature flag not found: {$key}");
 *     }
 * }
 * ```
 * @example Feature disabled
 * ```php
 * final class FeatureDisabledException extends FeatureFlagException
 * {
 *     public static function forFeature(string $feature): self
 *     {
 *         return new self("Feature is disabled: {$feature}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class FeatureFlagException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
