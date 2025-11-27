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
 * Exception for sampling operation failures.
 *
 * Thrown when sampling operations fail, sample size is invalid,
 * or sampling strategies encounter errors.
 *
 * @example Sampling failed
 * ```php
 * final class SamplingFailedException extends SampleException
 * {
 *     public static function failed(string $strategy, string $reason): self
 *     {
 *         return new self("Sampling using strategy '{$strategy}' failed: {$reason}");
 *     }
 * }
 * ```
 * @example Invalid sample size
 * ```php
 * final class InvalidSampleSizeException extends SampleException
 * {
 *     public static function detected(int $size, int $minSize, int $maxSize): self
 *     {
 *         return new self("Invalid sample size {$size}: must be between {$minSize} and {$maxSize}");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class SampleException extends ProfileException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
