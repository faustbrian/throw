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
 * Base exception for A/B testing failures.
 *
 * Thrown when experiment operations fail, variants are invalid,
 * or experiment assignment encounters errors.
 *
 * @example Experiment not found
 * ```php
 * final class ExperimentNotFoundException extends ExperimentException
 * {
 *     public static function forKey(string $key): self
 *     {
 *         return new self("Experiment not found: {$key}");
 *     }
 * }
 * ```
 * @example Invalid variant
 * ```php
 * final class InvalidVariantException extends ExperimentException
 * {
 *     public static function forExperiment(string $variant, string $experiment): self
 *     {
 *         return new self("Invalid variant '{$variant}' for experiment: {$experiment}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class ExperimentException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
