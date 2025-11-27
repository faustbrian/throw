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
 * Exception for backpressure handling failures.
 *
 * Thrown when backpressure handling fails, backpressure signals are ignored,
 * or backpressure operations encounter errors.
 *
 * @example Backpressure exceeded
 * ```php
 * final class BackpressureExceededException extends BackpressureException
 * {
 *     public static function detected(string $stream, int $bufferSize): self
 *     {
 *         return new self("Backpressure exceeded for stream '{$stream}': buffer size {$bufferSize}");
 *     }
 * }
 * ```
 * @example Backpressure signal ignored
 * ```php
 * final class BackpressureSignalIgnoredException extends BackpressureException
 * {
 *     public static function detected(string $consumer, string $reason): self
 *     {
 *         return new self("Backpressure signal ignored by consumer '{$consumer}': {$reason}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class BackpressureException extends ThrottleException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
