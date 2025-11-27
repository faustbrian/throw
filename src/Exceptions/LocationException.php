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
 * Base exception for location service errors.
 *
 * Thrown when location services fail, GPS data is unavailable,
 * or location-based operations encounter errors.
 *
 * @example Location unavailable
 * ```php
 * final class LocationUnavailableException extends LocationException
 * {
 *     public static function notFound(): self
 *     {
 *         return new self('User location unavailable');
 *     }
 * }
 * ```
 * @example Invalid coordinates
 * ```php
 * final class InvalidCoordinatesException extends LocationException
 * {
 *     public static function outOfRange(float $lat, float $lng): self
 *     {
 *         return new self("Invalid coordinates: {$lat}, {$lng}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class LocationException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
