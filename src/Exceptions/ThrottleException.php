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
 * Base exception for throttling operation errors.
 *
 * Thrown when throttling operations fail, throttle limits are exceeded,
 * or throttle operations encounter errors.
 *
 * @example Throttle limit exceeded
 * ```php
 * final class ThrottleLimitExceededException extends ThrottleException
 * {
 *     public static function detected(string $key, int $limit, int $period): self
 *     {
 *         return new self("Throttle limit exceeded for key '{$key}': {$limit} requests per {$period} seconds");
 *     }
 * }
 * ```
 * @example Throttle configuration invalid
 * ```php
 * final class InvalidThrottleConfigException extends ThrottleException
 * {
 *     public static function detected(string $throttle, string $reason): self
 *     {
 *         return new self("Invalid throttle configuration for '{$throttle}': {$reason}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class ThrottleException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
