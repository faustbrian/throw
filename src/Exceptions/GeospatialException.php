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
 * Base exception for geospatial query failures.
 *
 * Thrown when geospatial queries fail, spatial operations are invalid,
 * or geospatial processing encounters errors.
 *
 * @example Geospatial query failed
 * ```php
 * final class GeospatialQueryException extends GeospatialException
 * {
 *     public static function failed(string $query, string $reason): self
 *     {
 *         return new self("Geospatial query failed: {$query} - {$reason}");
 *     }
 * }
 * ```
 * @example Invalid spatial operation
 * ```php
 * final class InvalidSpatialOperationException extends GeospatialException
 * {
 *     public static function detected(string $operation, string $reason): self
 *     {
 *         return new self("Invalid spatial operation '{$operation}': {$reason}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class GeospatialException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
