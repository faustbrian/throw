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
 * Exception for coordinate validation errors.
 *
 * Thrown when coordinate validation fails, coordinates are out of range,
 * or coordinate operations encounter errors.
 *
 * @example Invalid coordinates
 * ```php
 * final class InvalidCoordinatesException extends CoordinateException
 * {
 *     public static function detected(float $latitude, float $longitude): self
 *     {
 *         return new self("Invalid coordinates: latitude={$latitude}, longitude={$longitude}");
 *     }
 * }
 * ```
 * @example Coordinates out of range
 * ```php
 * final class CoordinatesOutOfRangeException extends CoordinateException
 * {
 *     public static function detected(string $type, float $value, float $min, float $max): self
 *     {
 *         return new self("{$type} {$value} out of range: must be between {$min} and {$max}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class CoordinateException extends GeospatialException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
