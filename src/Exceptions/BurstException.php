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
 * Base exception for burst limit violations.
 *
 * Thrown when burst limits are exceeded, burst capacity is exhausted,
 * or burst rate limiting encounters errors.
 *
 * @example Burst limit exceeded
 * ```php
 * final class BurstLimitExceededException extends BurstException
 * {
 *     public static function forKey(string $key, int $limit): self
 *     {
 *         return new self("Burst limit of {$limit} requests exceeded for key: {$key}");
 *     }
 * }
 * ```
 * @example Burst capacity exhausted
 * ```php
 * final class BurstCapacityExhaustedException extends BurstException
 * {
 *     public static function detected(string $resource): self
 *     {
 *         return new self("Burst capacity exhausted for resource: {$resource}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class BurstException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
