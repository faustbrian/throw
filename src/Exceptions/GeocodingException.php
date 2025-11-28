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
 * Base exception for geocoding failures.
 *
 * Thrown when geocoding operations fail (address to coordinates,
 * reverse geocoding, etc.) or geocoding services are unavailable.
 *
 * @example Geocoding failed
 * ```php
 * final class GeocodingFailedException extends GeocodingException
 * {
 *     public static function forAddress(string $address): self
 *     {
 *         return new self("Failed to geocode address: {$address}");
 *     }
 * }
 * ```
 * @example Reverse geocoding failed
 * ```php
 * final class ReverseGeocodingException extends GeocodingException
 * {
 *     public static function failed(float $lat, float $lng): self
 *     {
 *         return new self("Failed to reverse geocode: {$lat}, {$lng}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class GeocodingException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
