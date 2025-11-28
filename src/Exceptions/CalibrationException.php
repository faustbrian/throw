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
 * Exception for system calibration failures.
 *
 * Thrown when system calibration fails, calibration is out of tolerance,
 * or calibration procedures encounter errors.
 *
 * @example Calibration failed
 * ```php
 * final class CalibrationFailedException extends CalibrationException
 * {
 *     public static function forSystem(string $system): self
 *     {
 *         return new self("Calibration failed for system: {$system}");
 *     }
 * }
 * ```
 * @example Out of tolerance
 * ```php
 * final class OutOfToleranceException extends CalibrationException
 * {
 *     public static function detected(string $metric, float $tolerance, float $actual): self
 *     {
 *         return new self("Calibration out of tolerance for '{$metric}': tolerance={$tolerance}, actual={$actual}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class CalibrationException extends OptimizationException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
