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
 * Base exception for data aggregation failures.
 *
 * Thrown when data aggregation fails, aggregation functions encounter errors,
 * or aggregation operations fail.
 *
 * @example Aggregation failed
 * ```php
 * final class AggregationFailedException extends AggregationException
 * {
 *     public static function forFunction(string $function, string $field): self
 *     {
 *         return new self("Aggregation failed using function '{$function}' on field: {$field}");
 *     }
 * }
 * ```
 * @example Invalid aggregation
 * ```php
 * final class InvalidAggregationException extends AggregationException
 * {
 *     public static function detected(string $function, string $reason): self
 *     {
 *         return new self("Invalid aggregation function '{$function}': {$reason}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class AggregationException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
