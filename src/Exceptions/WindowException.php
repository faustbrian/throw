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
 * Base exception for rate window errors.
 *
 * Thrown when rate limiting window operations fail, window calculations
 * are invalid, or sliding window tracking encounters errors.
 *
 * @example Window expired
 * ```php
 * final class WindowExpiredException extends WindowException
 * {
 *     public static function forKey(string $key, int $timestamp): self
 *     {
 *         return new self("Rate window expired for key '{$key}' at timestamp: {$timestamp}");
 *     }
 * }
 * ```
 * @example Invalid window size
 * ```php
 * final class InvalidWindowSizeException extends WindowException
 * {
 *     public static function detected(int $size): self
 *     {
 *         return new self("Invalid rate window size: {$size} seconds");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class WindowException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
