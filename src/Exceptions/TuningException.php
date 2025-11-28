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
 * Exception for performance tuning errors.
 *
 * Thrown when performance tuning fails, tuning parameters are invalid,
 * or tuning operations encounter errors.
 *
 * @example Tuning failed
 * ```php
 * final class TuningFailedException extends TuningException
 * {
 *     public static function forComponent(string $component): self
 *     {
 *         return new self("Performance tuning failed for component: {$component}");
 *     }
 * }
 * ```
 * @example Invalid tuning parameter
 * ```php
 * final class InvalidTuningParameterException extends TuningException
 * {
 *     public static function detected(string $parameter, mixed $value): self
 *     {
 *         return new self("Invalid tuning parameter '{$parameter}': " . json_encode($value));
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class TuningException extends OptimizationException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
