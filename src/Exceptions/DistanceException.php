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
 * Exception for distance calculation errors.
 *
 * Thrown when distance calculation fails, distance formulas are invalid,
 * or distance operations encounter errors.
 *
 * @example Distance calculation failed
 * ```php
 * final class DistanceCalculationException extends DistanceException
 * {
 *     public static function failed(string $formula, string $reason): self
 *     {
 *         return new self("Distance calculation using '{$formula}' failed: {$reason}");
 *     }
 * }
 * ```
 * @example Invalid distance unit
 * ```php
 * final class InvalidDistanceUnitException extends DistanceException
 * {
 *     public static function detected(string $unit, array $validUnits): self
 *     {
 *         return new self("Invalid distance unit '{$unit}': valid units are " . implode(', ', $validUnits));
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class DistanceException extends GeospatialException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
