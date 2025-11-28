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
 * Base exception for quota limit errors.
 *
 * Thrown when quota limits are exceeded, quota allocation fails,
 * or quota management encounters errors.
 *
 * @example Quota exceeded
 * ```php
 * final class QuotaExceededException extends QuotaException
 * {
 *     public static function forResource(string $resource, int $limit, int $used): self
 *     {
 *         return new self("Quota exceeded for '{$resource}': {$used}/{$limit} used");
 *     }
 * }
 * ```
 * @example Quota allocation failed
 * ```php
 * final class QuotaAllocationException extends QuotaException
 * {
 *     public static function failed(string $user, int $requested): self
 *     {
 *         return new self("Failed to allocate {$requested} quota units for user: {$user}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class QuotaException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
