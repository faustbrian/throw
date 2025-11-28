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
 * Base exception for metrics sampling failures.
 *
 * Thrown when sampling operations fail, sample rates are invalid,
 * or sampling strategies encounter errors.
 *
 * @example Invalid sample rate
 * ```php
 * final class InvalidSampleRateException extends SamplingException
 * {
 *     public static function outOfRange(float $rate): self
 *     {
 *         return new self("Invalid sample rate {$rate}: must be between 0 and 1");
 *     }
 * }
 * ```
 * @example Sampling buffer full
 * ```php
 * final class SamplingBufferFullException extends SamplingException
 * {
 *     public static function detected(int $size): self
 *     {
 *         return new self("Sampling buffer full at {$size} samples");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class SamplingException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
