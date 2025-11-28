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
 * Exception thrown when an iterator is exhausted.
 *
 * Thrown when attempting to advance an iterator that has reached the end of
 * its sequence. Useful for custom iterator implementations and generators.
 *
 * @example Iterator exhausted
 * ```php
 * final class IteratorExhaustedException extends StopIterationException
 * {
 *     public static function noMoreElements(): self
 *     {
 *         return new self("Iterator has been exhausted, no more elements available");
 *     }
 * }
 * ```
 * @example Generator complete
 * ```php
 * final class GeneratorCompleteException extends StopIterationException
 * {
 *     public static function withReturn(mixed $value): self
 *     {
 *         return new self("Generator completed with return value")
 *             ->withContext(['return_value' => $value]);
 *     }
 * }
 * ```
 *
 * @author Brian Faust <brian@cline.sh>
 */
abstract class StopIterationException extends RuntimeException
{
    use ConditionallyThrowable;
    use HasErrorContext;
    use WrapsErrors;
}
