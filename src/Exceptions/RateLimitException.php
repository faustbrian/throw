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
 * Base exception for rate limit violations.
 *
 * Thrown when a user or client exceeds allowed request rates or
 * usage quotas. Maps to HTTP 429 Too Many Requests.
 *
 * @example Request rate limit
 * ```php
 * final class TooManyRequestsException extends RateLimitException
 * {
 *     public static function perMinute(int $limit): self
 *     {
 *         return new self("Rate limit of {$limit} requests per minute exceeded");
 *     }
 * }
 * ```
 * @example API quota exceeded
 * ```php
 * final class QuotaExceededException extends RateLimitException
 * {
 *     public static function forResource(string $resource): self
 *     {
 *         return new self("Quota exceeded for {$resource}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class RateLimitException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
