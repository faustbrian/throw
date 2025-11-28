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
 * Base exception for delayed job errors.
 *
 * Thrown when delayed/deferred jobs fail to queue, execute, or when
 * delay configuration is invalid.
 *
 * @example Delayed job failed
 * ```php
 * final class DelayedJobException extends DelayedException
 * {
 *     public static function failed(string $job): self
 *     {
 *         return new self("Delayed job failed: {$job}");
 *     }
 * }
 * ```
 * @example Invalid delay
 * ```php
 * final class InvalidDelayException extends DelayedException
 * {
 *     public static function negative(): self
 *     {
 *         return new self('Delay cannot be negative');
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class DelayedException extends JobFailedException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
