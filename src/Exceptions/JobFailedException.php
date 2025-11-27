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
 * Base exception for queue job failures.
 *
 * Thrown when background jobs fail to process, timeout, or encounter
 * unrecoverable errors.
 *
 * @example Job processing failed
 * ```php
 * final class JobProcessingException extends JobFailedException
 * {
 *     public static function failed(string $job): self
 *     {
 *         return new self("Job processing failed: {$job}");
 *     }
 * }
 * ```
 * @example Job max attempts exceeded
 * ```php
 * final class JobMaxAttemptsException extends JobFailedException
 * {
 *     public static function exceeded(string $job, int $attempts): self
 *     {
 *         return new self("{$job} exceeded maximum {$attempts} attempts");
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class JobFailedException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
